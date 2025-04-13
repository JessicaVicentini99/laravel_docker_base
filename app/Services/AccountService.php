<?php


namespace App\Services;


use App\Jobs\ProcessTransactionJob;
use App\Repositories\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    public function store($data)
    {
        ProcessTransactionJob::dispatch($data['payer_id'], $data['payee_id'], $data['value']);
        return ['message' => 'Transação em processamento'];
    }

    public function getByUser()
    {
        $userId = auth()->user()->id;
        return $this->accountRepository->getByUserId($userId);
    }
}
