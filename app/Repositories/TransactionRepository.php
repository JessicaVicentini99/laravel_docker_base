<?php


namespace App\Repositories;


use App\Models\Transaction;
use Illuminate\Support\Facades\DB;

class TransactionRepository
{
    /**
     * @param $data
     * @return mixed
     */
    public function store($data)
    {
        return DB::transaction(function () use ($data) {
            return Transaction::create($data);
        });
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function findByUserId(int $userId)
    {
        return Transaction::where('from_user_id', $userId)
            ->orWhere('to_user_id', $userId)
            ->get();
    }
}
