<?php

namespace App\Jobs;

use App\Enums\TransactionStatusEnum;
use App\Models\Account;
use App\Models\Transaction;
use App\Models\User;
use App\Services\ProcessTransactionService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessTransactionJob implements ShouldQueue
{
    use Queueable;

    private ?int $payerId;
    private int $payeeId;
    private float $amount;

    /**
     * @param int|null $payerId
     * @param int $payeeId
     * @param float $amount
     */
    public function __construct(?int $payerId, int $payeeId, float $amount)
    {
        $this->payerId = $payerId;
        $this->payeeId = $payeeId;
        $this->amount = $amount;
    }

    /**
     * Execute the job to process a transaction.
     *
     * @param ProcessTransactionService $processTransactionService
     * @return void
     */
    public function handle(ProcessTransactionService $processTransactionService): void
    {
        DB::transaction(function () use ($processTransactionService) {
            $payeeAccount = $this->getAccount($this->payeeId);
            $user = User::find($this->payeeId);
            if (is_null($this->payerId)) {
                $this->processDeposit($payeeAccount);
            } else {
                $this->processTransfer($processTransactionService, $payeeAccount);
            }
        });
    }

    /**
     * Get the account associated with the user ID.
     *
     * @param int $userId
     * @return Account|null
     */
    private function getAccount(int $userId): ?Account
    {
        return Account::findByUserId($userId);
    }

    /**
     * Process a deposit transaction.
     *
     * @param Account $payeeAccount
     * @return void
     */
    private function processDeposit(Account $payeeAccount): void
    {
        $payeeAccount->increment('balance', $this->amount);

        $transaction = $this->createTransaction(
            null,
            $this->payeeId,
            $this->amount,
            true,
            TransactionStatusEnum::completed()
        );
        NotifyTransactionJob::dispatch($payeeAccount->user, $transaction);
    }

    /**
     * Process a transfer transaction.
     *
     * @param ProcessTransactionService $processTransactionService
     * @param Account $payeeAccount
     * @return void
     */
    private function processTransfer(ProcessTransactionService $processTransactionService, Account $payeeAccount): void
    {
        $payerAccount = $this->getAccount($this->payerId);

        if ($payerAccount->balance < $this->amount) {
            $this->createTransaction(
                $this->payerId,
                $this->payeeId,
                $this->amount,
                false,
                TransactionStatusEnum::failed(),
                'Insufficient balance'
            );
            dump('Insufficient balance');
            Log::warning('Transaction failed due to insufficient balance.');
            return;
        }

        if (!$processTransactionService->processTransaction()) {
            $this->createTransaction(
                $this->payerId,
                $this->payeeId,
                $this->amount,
                false,
                TransactionStatusEnum::failed(),
                'Unauthorized transaction'
            );
            dump('Unauthorized transaction');
            Log::warning('Unauthorized transaction.');
            return;
        }
        dump('Transaction authorized');
        try {
            Log::info('Transaction authorized.');
            $payerAccount->decrement('balance', $this->amount);
            $payeeAccount->increment('balance', $this->amount);
            $transaction = $this->createTransaction($this->payerId, $this->payeeId, $this->amount, true, TransactionStatusEnum::completed());
            NotifyTransactionJob::dispatch($payeeAccount->user, $transaction);
        } catch (\Throwable $exception) {
            dump($exception->getMessage());
        }

    }

    /**
     * Create a transaction record.
     *
     * @param int|null $fromUserId
     * @param int $toUserId
     * @param float $amount
     * @param bool $isReceived
     * @param TransactionStatusEnum $status
     * @return Transaction
     */
    private function createTransaction(
        ?int $fromUserId,
        int $toUserId,
        float $amount,
        bool $isReceived,
        TransactionStatusEnum $status,
        ?string $errorMessage = null
    ): Transaction {
        return Transaction::create([
            'from_user_id' => $fromUserId,
            'to_user_id' => $toUserId,
            'amount' => $amount,
            'is_received' => $isReceived,
            'status' => $status,
            'error_message' => $errorMessage
        ]);
    }
}
