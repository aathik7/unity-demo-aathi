<?php

use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TrackingController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', 'login');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['prefix' => 'inventory'],function () {
    Route::get('/index', [InventoryController::class, 'index'])->name('inventory.index');
    Route::get('/create', [InventoryController::class, 'create'])->name('inventory.create');
    Route::post('/store', [InventoryController::class, 'store'])->name('inventory.store');
    Route::get('/edit/{id}', [InventoryController::class, 'edit'])->name('inventory.edit');
    Route::post('/update', [InventoryController::class, 'update'])->name('inventory.update');
    Route::post('/destroy', [InventoryController::class, 'destroy'])->name('inventory.destroy');
    Route::get('/inventory-report', [InventoryController::class, 'report'])->name('inventory.report');
    Route::group(['prefix' => 'tracking'],function () {
        Route::get('/index', [TrackingController::class, 'index'])->name('tracking.index');
        Route::get('/edit', [TrackingController::class, 'edit'])->name('tracking.edit');
        Route::post('/update', [TrackingController::class, 'update'])->name('tracking.update');
        Route::get('/get-quantity', [TrackingController::class, 'getQuantity'])->name('tracking.quantity');
        Route::get('/check-empty-items', [TrackingController::class, 'checkEmptyItems'])->name('tracking.checkEmptyItems');
    });
});

require __DIR__.'/auth.php';
