<?php

namespace App\Services;

use App\Gateways\ProcessTransactionGateway;

class ProcessTransactionService
{
    private ProcessTransactionGateway $processTransaction;

    public function __construct(ProcessTransactionGateway $processTransaction)
    {
        $this->processTransaction = $processTransaction;
    }

    public function processTransaction()
    {
        return $this->processTransaction->processTransaction();
    }

}
