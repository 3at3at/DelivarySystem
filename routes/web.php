<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('admin.drivers');
    Route::post('/drivers/{id}/approve', [AdminController::class, 'approveDriver'])->name('admin.drivers.approve');
    Route::post('/drivers/{id}/suspend', [AdminController::class, 'suspendDriver'])->name('admin.drivers.suspend');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/loyalty', [AdminController::class, 'loyaltySettings'])->name('admin.loyalty');
    Route::post('/loyalty/update', [AdminController::class, 'updateLoyalty'])->name('admin.loyalty.update');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});

