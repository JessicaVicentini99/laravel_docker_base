<?php

namespace App\Jobs;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NotifyTransactionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private User $user;
    private Transaction $transaction;

    public $tries = 5; // tenta até 5 vezes
    public $backoff = [2, 5, 10, 15, 20, 25]; // espera progressivamente entre as tentativas

    /**
     * @param User $user
     * @param Transaction $transaction
     */
    public function __construct(User $user, Transaction $transaction)
    {
        $this->user = $user;
        $this->transaction = $transaction;
    }

    public function handle(): void
    {
        $payload = [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'message' => "Você recebeu uma transferência de R$ {$this->transaction->amount}!",
        ];
        try {
            $response = Http::post('https://util.devi.tools/api/v1/notify', $payload);

            if ($response->failed()) {
                Log::warning('Falha ao enviar notificação de transação');
                dump('Email failed');
                Log::warning('Email failed');
                throw new \Exception("Falha ao notificar usuário");
            }
            dump('Email sended');
            Log::info('Email sended');
        } catch (\Throwable $exception) {
            dump($exception->getMessage());
        }

    }
}
