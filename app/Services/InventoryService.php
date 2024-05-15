<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InventoryService
{

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
        return $this->inventoryItem->orderByDesc('active_flag')->orderByDesc('created_at')->get(['id', 'item_name', 'price', 'active_flag']);
    }

    /**
     * Fetch Inventory details from database
     * @return mixed
     */
    public function getUserDetails(): mixed
    {
        return User::where('id', Auth::user()->id)->first(['id', 'name', 'user_type']);
    }

    /*
     * Create new post
     *
     * @param  Form Data
     * @return Status
     */
    public function store($input)
    {
        try {
            $this->inventoryItem->create($input);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /*
     *
     * @param  Form Data
     * @return Status
     */
    public function update($input)
    {
        try {
            $this->inventoryItem->where('id', $input['id'])->update([
                'item_name' => $input['item_name'],
                'description' => $input['description'],
                'quantity' => $input['quantity'],
                'price' => $input['price']
            ]);
        } catch (\Exception $e) {
            Log::error($e);
        }
    }

    /*
     *
     * @param  $request
     * @return Status
     */
    public function destroy($request)
    {
        $this->inventoryItem->where('id', $request['id'])->delete();
    }

    /**
     * Fetch Inventory details from database
     * @param $id
     * @return mixed
    */
    public function getDetails($id): mixed
    {
        return $this->inventoryItem->where('id', $id)->first(['id', 'item_name', 'description', 'quantity', 'price', 'active_flag']);
    }
}
