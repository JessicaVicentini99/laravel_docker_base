<?php


namespace App\Repositories;


use App\Models\Account;
use App\Models\Transaction;

class AccountRepository
{

    /**
     * @param $userId
     * @return mixed
     */
    public function getByUserId($userId)
    {
        return Account::findByUserId($userId);
    }


}
