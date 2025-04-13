<?php

namespace App\Services;

use App\Gateways\ProcessTransactionGateway;

class ProcessTransactionService
{
    private ProcessTransactionGateway $processTransaction;

    /**
     * @param ProcessTransactionGateway $processTransaction
     */
    public function __construct(ProcessTransactionGateway $processTransaction)
    {
        $this->processTransaction = $processTransaction;
    }

    /**
     * @return bool
     */
    public function processTransaction()
    {
        return $this->processTransaction->processTransaction();
    }

}
