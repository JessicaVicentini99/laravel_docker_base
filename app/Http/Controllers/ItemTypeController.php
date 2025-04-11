<?php

namespace App\Http\Controllers;

use App\Http\Requests\ItemTypeStoreRequest;
use App\Http\Requests\ItemTypeUpdateRequest;
use App\Http\Resources\ItemTypeDetailsResource;
use App\Http\Resources\ItemTypeResource;
use App\Services\ItemTypeService;
use Illuminate\Http\Request;

class ItemTypeController extends Controller
{
    private $itemTypeService;

    public function __construct(ItemTypeService $itemTypeService)
    {
        $this->itemTypeService = $itemTypeService;
    }

    public function get()
    {
        $items = $this->itemTypeService->get();

        return ItemTypeResource::collection($items);
    }

    public function store(ItemTypeStoreRequest $request)
    {
        $data = $request->validated();
        $item = $this->itemTypeService->store($data);

        return new ItemTypeResource($item);
    }

    public function details($id)
    {
        $item = $this->itemTypeService->details($id);

        if ($item) {
            return new ItemTypeDetailsResource($item);
        }
        return response()->json([
            "message" => "Tipo de Item nao encotrado",
        ], 404);
    }

    public function update(ItemTypeUpdateRequest $request, $id)
    {
        $data = $request->validated();
        $item = $this->itemTypeService->update($id, $data);
        if ($item) {
            return new ItemTypeResource($item);
        }

        return response()->json([
            "message" => "Tipo de Item nao encotrado",
        ], 404);
    }

    public function destroy($id)
    {
        $item = $this->itemTypeService->destroy($id);
        if ($item) {
            return new ItemTypeResource($item);
        }

        return response()->json([
            "message" => "Tipo de Item nao encotrado",
        ], 404);
    }
}
