<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeliveryAddressResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "number" => $this->number,
            "address" => $this->address,
            "district" => $this->district,
            "city" => $this->city,
            "state" => $this->state,
        ];
    }
}
