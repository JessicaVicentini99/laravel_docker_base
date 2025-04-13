<?php


namespace App\Services;


use App\Jobs\ProcessTransactionJob;
use App\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function store($data)
    {
        ProcessTransactionJob::dispatch($data['payer_id'], $data['payee_id'], $data['value']);
        return ['message' => 'Transação em processamento'];
    }

    public function getByUser()
    {
        $user = auth()->user();
        return $this->transactionRepository->findByUserId($user->id);
    }
}
