<?php


namespace App\Services;


use App\Jobs\ProcessTransactionJob;
use App\Repositories\AccountRepository;

class AccountService
{
    private AccountRepository $accountRepository;

    /**
     * @param AccountRepository $accountRepository
     */
    public function __construct(AccountRepository $accountRepository)
    {
        $this->accountRepository = $accountRepository;
    }

    /**
     * @param $data
     * @return string[]
     */
    public function store($data)
    {
        ProcessTransactionJob::dispatch($data['payer_id'], $data['payee_id'], $data['value']);
        return ['message' => 'Transação em processamento'];
    }

    /**
     * @return mixed
     */
    public function getByUser()
    {
        $userId = auth()->user()->id;
        return $this->accountRepository->getByUserId($userId);
    }
}
