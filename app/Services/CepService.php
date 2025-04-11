<?php

namespace App\Services;

use App\Gateways\ViaCepGateway;

class CepService
{
    private ViaCepGateway $viaCepGateway;

    public function __construct(ViaCepGateway $viaCepGateway)
    {
        $this->viaCepGateway = $viaCepGateway;
    }

    public function getAddressByCep($cep){
        return $this->viaCepGateway->findAddressByCep($cep);
    }

}
