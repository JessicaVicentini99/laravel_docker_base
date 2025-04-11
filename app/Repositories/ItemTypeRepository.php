<?php


namespace App\Repositories;


use App\Models\ItemType;

class ItemTypeRepository
{
    public function get()
    {
        return ItemType::all();
    }

    public function store($data)
    {
        return ItemType::create($data);
    }

    public function details($id)
    {
        return ItemType::find($id);
    }

    public function update($id, $data)
    {
        $itemType = ItemType::find($id);
        if ($itemType) {
            $itemType->update($data);
            return $itemType;
        }
        return null;
    }

    public function destroy($id)
    {
        $itemType = ItemType::find($id);
        if ($itemType) {
            $itemType->delete();
            return $itemType;
        }
        return null;
    }

}
