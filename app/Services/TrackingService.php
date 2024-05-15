<?php

namespace App\Services;

use App\Models\InventoryItem;

class TrackingService
{
    protected const MINIMUM_QUANTITY = 5;
    protected InventoryItem $inventoryItem;
    /**
     * InventoryController's Constructor
     *
     */
    function __construct(InventoryItem $inventoryItem)
    {
        $this->inventoryItem = $inventoryItem;
    }

    /**
     * Fetch Inventory details from database
     * @return mixed
     */
    public function getItemList(): mixed
    {
        return $this->inventoryItem->orderByDesc('active_flag')->orderBy('quantity')->orderByDesc('created_at')->get(['id', 'item_name', 'quantity', 'active_flag']);
    }

    /**
     * @return mixed
     */
    public function checkEmptyItems(): mixed
    {
        return $this->inventoryItem->where('quantity', '<', self::MINIMUM_QUANTITY)->count();
    }

    /**
     * Fetch Stock details from database
     * @return mixed
     */
    public function getStockList(): mixed
    {
        return $this->inventoryItem->orderByDesc('active_flag')->orderBy('quantity')->orderByDesc('created_at')->get(['id', 'item_name']);
    }

    /**
     * Fetch Stock details from database
     * @return mixed
     */
    public function updateStock($params): mixed
    {
        return $this->inventoryItem->where('id', $params['item_id'])->update(['quantity' => $params['quantity']]);
    }

    /**
     * Fetch Stock Quantity from database
     * @param $itemId
     * @return mixed
     */
    public function getQuantity($itemId): mixed
    {
        return $this->inventoryItem->where('id', $itemId)->pluck('quantity');
    }
}
