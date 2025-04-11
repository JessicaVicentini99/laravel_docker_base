<?php

namespace App\Jobs;

use App\Enums\PaymentStatus;
use App\Services\ProcessPaymentService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessPaymentJob implements ShouldQueue
{
    use Queueable;

    private mixed $purchase;
    private mixed $payment_data;


    /**
     * Create a new job instance.
     */
    public function __construct($purchase,$payment_data)
    {
        $this->purchase = $purchase;
        $this->payment_data = $payment_data;
    }

    /**
     * Execute the job.
     */
    public function handle(ProcessPaymentService $processPaymentService): void
    {
        $payment_status = $processPaymentService->processPayment($this->payment_data);

        if ($payment_status) {
            $this->purchase->payment_status = PaymentStatus::COMPLETED;
        } else {
            $this->purchase->payment_status = PaymentStatus::FAILED;
        }
        $this->purchase->save();
    }
}
