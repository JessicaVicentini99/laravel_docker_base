<?php


namespace App\Services;


use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Repositories\ItemTypeRepository;

class ItemService
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;

    }

    public function get()
    {
        return $this->itemRepository->get();
    }

    public function store($data)
    {
        return $this->itemRepository->store($data);
    }

    public function details($id)
    {
        return $this->itemRepository->details($id);
    }

    public function update($id, $data)
    {
        return $this->itemRepository->update($id, $data);
    }

    public function destroy($id)
    {
        return $this->itemRepository->destroy($id);
    }

}
