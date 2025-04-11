<?php


namespace App\Repositories;


use App\Models\Purchase;

class PurchaseRepository
{
    public function store($data)
    {
        return Purchase::create($data);
    }

    public function get()
    {
        return Purchase::all();
    }

    public function details($id)
    {
        return Purchase::find($id);
    }

    public function getWithUserId($userId)
    {
        return Purchase::where('user_id', $userId)->get();
    }

    public function detailsWithUserId($id, $userId)
    {
        return Purchase::where('user_id', $userId)->find($id);
    }


}
