<?php

namespace App\Jobs;

use App\Models\Account;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreateBankAccountForUserJob implements ShouldQueue
{
    use Queueable;

    private User $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * xecute the job to create a bank account for the associated user.
     *
     * @return void
     */
    public function handle(): void
    {
        try {
            // Criar a conta bancária para o usuário
            $account = Account::create([
                'user_id' => $this->user->id,
                'agency' => '0001',  // Agência padrão
                'number' => $this->generateUniqueAccountNumber(),  // Gerar número da conta
                'balance' => 0.00,  // Saldo inicial
            ]);

            Log::info('Conta bancária criada para o usuário', [
                'user_id' => $this->user->id,
                'account_id' => $account->id,
            ]);
        } catch (\Exception $e) {
            Log::error('Erro ao criar conta bancária', [
                'user_id' => $this->user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Generate a unique account number (e.g., 123456-0).
     *
     * @return string
     */
    protected function generateUniqueAccountNumber(): string
    {
        do {
            $base = str_pad(mt_rand(0, 999999999), 9, '0', STR_PAD_LEFT); // 9 dígitos
            $digit = mt_rand(0, 9); // dígito verificador
            $accountNumber = "{$base}-{$digit}";
        } while (Account::where('number', $accountNumber)->exists());
        return $accountNumber;
    }
}
