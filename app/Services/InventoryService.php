<?php

namespace App\Services;

use App\Models\InventoryItem;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        return $this->inventoryItem->orderByDesc('active_flag')->orderByDesc('created_at')->get(['id', 'item_name', 'quantity', 'price', 'active_flag']);
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

    /**
     * @param $request
     * @return mixed
    */
    public function search($request)
    {
        $output = '';
        $items = $this->inventoryItem->where('item_name','ILIKE','%'.$request['key']."%")->get(['id', 'item_name', 'description', 'quantity', 'price', 'active_flag'])->toArray();
        $getUserDetails = $this->getUserDetails();
        if(!empty($items)) {
            foreach ($items as $key => $item) {
                $status = $item["active_flag"] ? "Active" : "Inactive";
                $output.= '<tr ';
                if($item['quantity'] < 5) {
                    $output.= 'style="background-color: coral;"';
                }
                $output.= '>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    '.$item['item_name'].'
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    '.$item['price'].'
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                '.$status.'
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="/inventory/edit/'.$item['id'].'" class="text-indigo-600 hover:text-indigo-900 mb-2 mr-2">Edit</a>';

                if(!empty($getUserDetails) && $getUserDetails["user_type"] == 1) {
                    $output.= '<a data-toggle="modal" onclick="enablePopup('.$item['id'].')  class="text-red-600 hover:text-red-900 mb-2 mr-2 deleteBtn" name="deleteBtn" title="Delete Item">Delete</a>';
                }     
                $output.= '</td>
                </tr>';
            }
        }
        return Response($output);
    }

}
