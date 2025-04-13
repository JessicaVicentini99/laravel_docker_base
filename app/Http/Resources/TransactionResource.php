<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "amount" => $this->amount,
            "is_received" => $this->is_received,
            "status" => $this->status,
            "from_user" => $this->fromUser ? new UserResource($this->fromUser) : null,
            "to_user" => $this->toUser ? new UserResource($this->toUser) : null,
            "created_at" => $this->created_at,
            "updated_at" => $this->created_at,
        ];
    }
}
