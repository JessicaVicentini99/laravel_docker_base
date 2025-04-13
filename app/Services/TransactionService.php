<?php


namespace App\Services;


use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Jobs\ProcessPaymentJob;
use App\Jobs\ProcessTransactionJob;
use App\Repositories\ItemRepository;
use App\Repositories\TransactionRepository;

class TransactionService
{
    private TransactionRepository $transactionRepository;

    public function __construct(TransactionRepository $transactionRepository)
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function store($data)
    {
        ProcessTransactionJob::dispatch($data['payer_id'], $data['payee_id'], $data['value']);
        return ['message' => 'Transação em processamento'];
    }

    public function getByUser()
    {
        $user = auth()->user();
        return $this->transactionRepository->findByUserId($user->id);
    }
//
//    public function details($id)
//    {
//        $user = auth()->user();
//        return $this->purchaseRepository->detailsWithUserId($id, $user->id);
//    }
//
//    public function reprocessPayment($id, $data)
//    {
//        $purchase = $this->details($id);
//        if ($purchase && $purchase->payment_status == PaymentStatus::FAILED && $purchase->payment_type == PaymentType::CREDIT_CARD) {
//
//            $purchase->payment_status = PaymentStatus::PROCESSING;
//            $purchase->save();
//
//            $total = 0;
//            foreach ($purchase->items as $item) {
//                $total += $item->item_price * $item->item_quantity;
//            }
//
//            $data['value'] = $total;
//            ProcessPaymentJob::dispatch($purchase, $data);
//            return $purchase;
//        }
//
//        return null;
//    }
}
