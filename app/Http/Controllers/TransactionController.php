<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransactionStoreRequest;
use App\Http\Resources\TransactionResource;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function store(TransactionStoreRequest $request)
    {
        $data = $request->validated();
        $this->transactionService->store($data);
        return response()->json([
            'message' => 'Transação em processamento'
        ], 202);
    }

    public function getByUser()
    {
        $transactions = $this->transactionService->getByUser();
        return TransactionResource::collection($transactions);
    }
}
