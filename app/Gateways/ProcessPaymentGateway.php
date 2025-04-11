<?php

namespace App\Gateways;

use Illuminate\Support\Facades\Http;

class ProcessPaymentGateway
{
    public function processPayment($payment_data)
    {

        $response = Http::post("http://127.0.0.1:5000/process_payment", $payment_data);

        $response_data = $response->json();
        if ($response_data['status'] == 'success') {
            return true;
        }
        return false;
    }


}
