<?php


namespace App\Repositories;


use App\Models\Account;
use App\Models\Purchase;
use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            return Transaction::create($data);
        });
    }

    public function findById($id)
    {
        return Transaction::find($id);
    }

    public function findByUserId(int $userId)
    {
        return Transaction::where('from_user_id', $userId)
            ->orWhere('to_user_id', $userId)
            ->get();
    }

    public function getWithUserId($userId)
    {
        return Transaction::where('user_id', $userId)->get();
    }

    public function detailsWithUserId($id, $userId)
    {
        return Purchase::where('user_id', $userId)->find($id);
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
