<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

//        dd($items_data);
        return [
            "id" => $this->id,
            "name" => $this->name,
            "description" => $this->description,
        ];
    }

}
