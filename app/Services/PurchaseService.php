<?php


namespace App\Services;


use App\Enums\PaymentStatus;
use App\Enums\PaymentType;
use App\Jobs\ProcessPaymentJob;
use App\Repositories\ItemRepository;
use App\Repositories\PurchaseRepository;

class PurchaseService
{
    private PurchaseRepository $purchaseRepository;
    private ItemRepository $itemRepository;
    private CepService $cepService;
    private ProcessPaymentService $processPaymentService;

    public function __construct(PurchaseRepository $purchaseRepository, ItemRepository $itemRepository, CepService $cepService, ProcessPaymentService $processPaymentService)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->itemRepository = $itemRepository;
        $this->cepService = $cepService;
        $this->processPaymentService = $processPaymentService;
    }

    public function store($data)
    {
        $data['payment_status'] = PaymentStatus::PENDING;

        $user = auth()->user();
        $data['user_id'] = $user->id;

        $purchase = $this->purchaseRepository->store($data);

        $total_value = 0;
        foreach ($data['items'] as $item_data) {
            $item = $this->itemRepository->details($item_data['item_id']);
            if ($item) {
                $item_data['item_price'] = $item->price;
                $purchase->items()->create($item_data);
                $total_value += $item->price * $item_data['item_quantity'];
            }
        }


        $address_data = $this->cepService->getAddressByCep($data['address']['cep']);
        if ($address_data) {
            $address_data['number'] = $data['address']['number'];
            $purchase->delivery_address()->create($address_data);
        }

        if ($data['payment_type'] == PaymentType::CREDIT_CARD) {
            $purchase->payment_status = PaymentStatus::PROCESSING;
            $purchase->save();
            $data['payment_data']['value'] = $total_value;
            ProcessPaymentJob::dispatch($purchase, $data['payment_data']);
        }

        return $purchase;
    }

    public function get()
    {
        $user = auth()->user();
        return $this->purchaseRepository->getWithUserId($user->id);
    }

    public function details($id)
    {
        $user = auth()->user();
        return $this->purchaseRepository->detailsWithUserId($id, $user->id);
    }

    public function reprocessPayment($id, $data)
    {
        $purchase = $this->details($id);
        if ($purchase && $purchase->payment_status == PaymentStatus::FAILED && $purchase->payment_type == PaymentType::CREDIT_CARD) {

            $purchase->payment_status = PaymentStatus::PROCESSING;
            $purchase->save();

            $total = 0;
            foreach ($purchase->items as $item) {
                $total += $item->item_price * $item->item_quantity;
            }

            $data['value'] = $total;
            ProcessPaymentJob::dispatch($purchase, $data);
            return $purchase;
        }

        return null;
    }
}
