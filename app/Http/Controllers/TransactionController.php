<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\ReprocessPaymentRequest;
use App\Http\Requests\TransactionStoreRequest;
use App\Http\Resources\PurchaseDetailsResource;
use App\Http\Resources\PurchaseResource;
use App\Http\Resources\TransactionResource;
use App\Services\PurchaseService;
use App\Services\TransactionService;
use http\Env\Response;
use Illuminate\Http\Request;

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

    public function details($id)
    {
        $transaction = $this->transactionService->details($id);
        if ($transaction) {
            return new PurchaseDetailsResource($transaction);
        }

        return response()->json([
            "message" => "Purchase not found",
        ], 404);
    }

    public function reprocessPayment($id, ReprocessPaymentRequest $request)
    {
        $data = $request->validated();
        $transaction = $this->transactionService->reprocessPayment($id, $data);

        if ($transaction) {
            return new PurchaseDetailsResource($transaction);
        }

        return response()->json([
            "message" => "Purchase not found",
        ], 404);
    }

}
