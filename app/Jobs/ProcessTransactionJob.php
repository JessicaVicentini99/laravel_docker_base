<?php

namespace App\Jobs;

use App\Enums\TransactionStatusEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
//use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ProcessTransactionJob implements ShouldQueue
{
    use Queueable;

    private ?int $payerId; // Agora aceita null
    private int $payeeId;
    private float $amount;

    public function __construct(?int $payerId, int $payeeId, float $amount)
    {
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
        $this->amount = $amount;
    }

    public function handle(): void
    {
        DB::transaction(function () {
            $payeeAccount = $this->getAccount($this->payeeId);

            if (is_null($this->payerId)) {
                // Caso de depósito
                $payeeAccount->increment('balance', $this->amount);
                $transaction = Transaction::create([
                    'from_user_id' => null,
                    'to_user_id' => $this->payeeId,
                    'amount' => $this->amount,
                    'is_received' => true,
                    'status' => TransactionStatusEnum::completed(),
                ]);
                $this->notifyUser($this->payeeId, $transaction);
//                dd($transaction);
            } else {
                // Caso de transferência
                $payerAccount = $this->getAccount($this->payerId);

                if ($payerAccount->balance < $this->amount) {
                    Transaction::create([
                        'from_user_id' => $this->payerId,
                        'to_user_id' => $this->payeeId,
                        'amount' => $this->amount,
                        'is_received' => false,
                        'status' => TransactionStatusEnum::failed(),
                    ]);
                    return;
                }
                $response = Http::get('https://util.devi.tools/api/v2/authorize');

                if ($response->failed() || !$response->json('data.authorization')) {
                    // Serviço negou ou falhou
                    Transaction::create([
                        'from_user_id' => $this->payerId,
                        'to_user_id' => $this->payeeId,
                        'amount' => $this->amount,
                        'is_received' => false,
                        'status' => TransactionStatusEnum::failed(),
                    ]);
                    return;
                }

                $payerAccount->decrement('balance', $this->amount);
                $payeeAccount->increment('balance', $this->amount);

                $transaction = Transaction::create([
                    'from_user_id' => $this->payerId,
                    'to_user_id' => $this->payeeId,
                    'amount' => $this->amount,
                    'is_received' => true,
                    'status' => TransactionStatusEnum::completed(),
                ]);

                $this->notifyUser($this->payeeId, $transaction);
            }
        });
    }

    private function getAccount(int $userId): ?Account
    {
        //TODO implementar findUserbyid
        return Account::where('user_id', $userId)->first();
    }

    private function notifyUser(int $userId, Transaction $transaction): void
    {
        try {
            $user = User::find($userId);

            // Dados que serão enviados para o mock de notificação
            $payload = [
                'user_id' => $userId,
                'email' => $user->email,
                'message' => "Você recebeu uma transferência de R$ {$transaction->amount}!",
            ];

            $response = Http::post('https://util.devi.tools/api/v1/notify', $payload);

            if ($response->failed()) {
                Log::warning('Falha ao enviar notificação de transação', [
                    'user_id' => $userId,
                    'response' => $response->body(),
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Erro ao tentar notificar usuário', [
                'user_id' => $userId,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
