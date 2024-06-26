<?php

namespace App\Http\Controllers;

use App\Exports\InventoryExport;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class InventoryController extends Controller
{
    protected InventoryService $inventoryService;
    protected $itemImageName;
    protected $s3FolderName;

    /**
     * InventoryController's Constructor
     * 
     * @param InventoryService $inventoryService
     */
    function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
        $this->itemImageName = 'item_image_' . time();
        $this->s3FolderName = '/items/';
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = $this->inventoryService->getUserDetails();
        $items = $this->inventoryService->getItemList();
        return view('inventory.index', ['items' => $items, 'user' => $user]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('inventory.create', ['title' => 'Create Item']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|string',
            'price' => 'required|string'
        ]);
        if ($validator->fails()) {
            return Redirect::route('inventory.index')->withErrors($validator);
        }
        $itemImage = '';
        if (isset($request->item_image)) {
            $this->itemImageName = $this->itemImageName . '.' . $request->item_image->getClientOriginalExtension();
            $itemImage = $this->inventoryService->fileUpload($request->item_image, $this->itemImageName, $this->s3FolderName);
        }
        $this->inventoryService->store($request->all(), $itemImage);
        return redirect()->route('inventory.index')->with('success','Item created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     * @param $id
     */
    public function edit(string $id)
    {
        $itemDetails = $this->inventoryService->getDetails($id);
        return view('inventory.edit', ['item' => $itemDetails, 'title' => 'Edit Item']);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'item_name' => 'required|string',
            'description' => 'required|string',
            'quantity' => 'required|string',
            'price' => 'required|string'
        ]);
        if ($validator->fails()) {
            return Redirect::route('inventory.index')->withErrors($validator);
        }
        $itemImage = $request->old_item_image;
        if (isset($request->item_image)) {
            $this->itemImageName = $this->itemImageName . '.' . $request->item_image->getClientOriginalExtension();
            $itemImage = $this->inventoryService->fileUpload($request->item_image, $this->itemImageName, $this->s3FolderName);
        }
        $this->inventoryService->update($request->all(), $itemImage);
        return redirect()->route('inventory.index')->with('success','Item Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        Log::info($request->all());
        $validator = Validator::make($request->all(), ['id' => 'required']);
        if ($validator->fails()) {
            return Redirect::route('inventory.index')->withErrors($validator);
        }
        $this->inventoryService->destroy($request->all());
        return redirect()->route('inventory.index')->with('success','Item Deleted successfully.');
    }

    /**
     * @return void
     */
    public function search(Request $request)
    {
        return $this->inventoryService->search($request->all());
    }

    /**
     * @return void
     */
    public function report()
    {
        return Excel::download(new InventoryExport, 'inventory.xlsx');
    }
}
