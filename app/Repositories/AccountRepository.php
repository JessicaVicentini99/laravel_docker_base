<?php


namespace App\Repositories;


use App\Models\Account;
use App\Models\Transaction;

class AccountRepository
{
    public function getByUserId($userId)
    {
        return Account::findByUserId($userId);
    }
    public function findByAccount(int $accountId)
    {
        $userId = Account::where('id', $accountId)
            ->value('user_id');

        if (!$userId) {
            return collect(); // ou lançar exceção
        }

        return Transaction::where('from_user_id', $userId)
            ->orWhere('to_user_id', $userId)
            ->get();
    }


}
