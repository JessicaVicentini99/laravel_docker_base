<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Jobs\ProcessPaymentJob;
use App\Services\CepService;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    private CepService $cepService;

    public function __construct(CepService $cepService)
    {
        $this->cepService = $cepService;
    }

    public function searchCep($cep)
    {
        ProcessPaymentJob::dispatch('teste',$cep)->onQueue('banana');


        $address = $this->cepService->getAddressByCep($cep);
        if ($address) {
            return new AddressResource((object)$address);
        }

        return response()->json(['message' => "Address not found"], 404);
    }

}
