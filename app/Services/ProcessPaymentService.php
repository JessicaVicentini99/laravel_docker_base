<?php

namespace App\Services;

use App\Gateways\ProcessPaymentGateway;

class ProcessPaymentService
{
    private ProcessPaymentGateway $processPaymentGateway;

    public function __construct(ProcessPaymentGateway $processPaymentGateway)
    {
        $this->processPaymentGateway = $processPaymentGateway;
    }

    public function processPayment($payment_data)
    {
        return $this->processPaymentGateway->processPayment($payment_data);
    }

}
