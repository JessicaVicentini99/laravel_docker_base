<?php

namespace App\Gateways;

use Illuminate\Support\Facades\Http;

class ProcessTransactionGateway
{
    public function processTransaction()
    {

        $response = Http::get('https://util.devi.tools/api/v2/authorize');

        if ($response->failed() || !$response->json('data.authorization')) {
            return false;
        }
        return true;
    }


}
