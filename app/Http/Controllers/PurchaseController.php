<?php

namespace App\Http\Controllers;

use App\Http\Requests\PurchaseStoreRequest;
use App\Http\Requests\ReprocessPaymentRequest;
use App\Http\Resources\PurchaseDetailsResource;
use App\Http\Resources\PurchaseResource;
use App\Services\PurchaseService;
use http\Env\Response;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    private $purchaseService;
    public function __construct(PurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    public function store(PurchaseStoreRequest $request){
        $data = $request->validated();
        $purchase = $this->purchaseService->store($data);
        return new PurchaseResource($purchase);
    }

    public function get(){
        $purchases = $this->purchaseService->get();
        return PurchaseResource::collection($purchases);
    }

    public function details($id){
        $purchase = $this->purchaseService->details($id);
        if($purchase){
            return new PurchaseDetailsResource($purchase);
        }

        return response()->json([
            "message" => "Purchase not found",
        ], 404);
    }

    public function reprocessPayment($id, ReprocessPaymentRequest $request){
        $data = $request->validated();
        $purchase = $this->purchaseService->reprocessPayment($id, $data);

        if($purchase){
            return new PurchaseDetailsResource($purchase);
        }

        return response()->json([
            "message" => "Purchase not found",
        ], 404);
    }

}
