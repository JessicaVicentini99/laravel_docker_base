<?php

namespace App\Gateways;

use Illuminate\Support\Facades\Http;

class ViaCepGateway
{
    public function findAddressByCep($cep)
    {
        $response = Http::get('viacep.com.br/ws/' . $cep . '/json/');
        $data = $response->json();
        if ($data) {
            if (!isset($data['erro'])) {
                return [
                    "address" => $data['logradouro'],
                    "district" => $data['bairro'],
                    "city" => $data['localidade'],
                    "state" => $data['estado'],
                ];
            }
        }

        return null;
    }

}
