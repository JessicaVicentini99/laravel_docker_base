<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountDetailsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "agency" => $this->agency,
            "number" => $this->number,
            "balance" => $this->balance,
            "user" => new UserResource($this->user),
            "created_at" => $this->created_at,
            "updated_at" => $this->created_at,
        ];
    }
}
