<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemStoreRequest;
use App\Http\Requests\ItemUpdateRequest;
use App\Http\Resources\ItemResource;
use App\Services\ItemService;

class ItemController extends Controller
{
    private $itemService;

    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;
    }

    public function get()
    {
        $items = $this->itemService->get();

        return ItemResource::collection($items);
    }

    public function store(ItemStoreRequest $request)
    {
        $data = $request->validated();
        $item = $this->itemService->store($data);

        return new ItemResource($item);
    }

    public function details($id)
    {
        $item = $this->itemService->details($id);

        if ($item) {
            return new ItemResource($item);
        }
        return response()->json([
            "message" => "Item nao encotrado",
        ], 404);
    }

    public function update(ItemUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $item = $this->itemService->update($id, $data);
        if ($item) {
            return new ItemResource($item);
        }

        return response()->json([
            "message" => "Item nao encotrado",
        ], 404);
    }

    public function destroy($id)
    {
        $item = $this->itemService->destroy($id);
        if ($item) {
            return new ItemResource($item);
        }

        return response()->json([
            "message" => "Item nao encotrado",
        ], 404);
    }
}
