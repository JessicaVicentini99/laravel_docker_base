<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemTypeDetailsResource extends JsonResource
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
            "items" => $this->getItemsData($this->items)
        ];
    }

    private function getItemsData($items)
    {
        $items_data = [];
        foreach ($items as $item) {
            array_push($items_data, [
                "name" => $item->name,
                "description" => $item->description,
                "price" => $item->price,
            ]);
        }
        return $items_data;
    }
}
