<?php

namespace App\Http\Controllers;

use App\Services\TrackingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class TrackingController extends Controller
{
    protected TrackingService $trackingService;

    /**
     * TrackingController's Constructor
     * 
     * @param TrackingService $trackingService
     */
    function __construct(TrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $inventoryItems = $this->trackingService->getItemList();
        return view('tracking.index', ['inventoryItems' => $inventoryItems]);
    }

    /**
     * @return bool
     */
    public function checkEmptyItems()
    {
        return $this->trackingService->checkEmptyItems();
    }

    /**
     * Display for edit the listing of the resource.
     */
    public function edit()
    {
        $stocks = $this->trackingService->getStockList();
        return view('tracking.edit', ['title' => 'Update Stocks', 'stocks' => $stocks]);
    }

    /**
     * Display for edit the listing of the resource.
     */
    public function update(Request $request)
    {
        $stocks = $this->trackingService->updateStock($request->all());
        return Redirect::route('tracking.index');
    }

    /**
     * Display for edit the listing of the resource.
     */
    public function getQuantity(Request $request)
    {
        return $this->trackingService->getQuantity($request['id']);
    }
}
