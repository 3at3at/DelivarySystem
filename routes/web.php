<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;


use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\DriverDeliveryController;
use App\Http\Controllers\DriverAvailabilityController;
use App\Http\Controllers\DriverEarningsController;
Route::get('/', function () {
    return view('welcome');
});

Route::prefix('driver')->group(function () {                                                                                                     
    Route::get('/register', [DriverAuthController::class, 'showRegisterForm'])->name('driver.register');
    Route::post('/register', [DriverAuthController::class, 'register']);
    Route::get('/login', [DriverAuthController::class, 'showLoginForm'])->name('driver.login');
    Route::post('/login', [DriverAuthController::class, 'login']);
    Route::middleware('auth:driver')->group(function () {
        Route::get('/dashboard', function () {
            return view('drivers.dashboard');
        })->name('driver.dashboard');
        Route::post('/logout', [DriverAuthController::class, 'logout'])->name('driver.logout');
    });
});
Route::middleware('auth:driver')->prefix('driver')->group(function () {
    Route::get('/deliveries', [DriverDeliveryController::class, 'index'])->name('driver.deliveries');
    Route::post('/deliveries/{id}/update-status', [DriverDeliveryController::class, 'updateStatus'])->name('driver.delivery.update');
});
Route::middleware('auth:driver')->prefix('driver')->group(function () {
    Route::get('/availability', [DriverAvailabilityController::class, 'edit'])->name('driver.availability');
    Route::post('/availability', [DriverAvailabilityController::class, 'update']);
});
Route::middleware('auth:driver')->prefix('driver')->group(function () {
    Route::get('/calendar', [DriverDeliveryController::class, 'calendar'])->name('driver.calendar');
});
Route::middleware('auth:driver')->prefix('driver')->group(function () {
    Route::get('/earnings', [DriverEarningsController::class, 'index'])->name('driver.earnings');
});
Route::post('/driver/save-token', [DriverAuthController::class, 'saveToken']);


Route::middleware('adminpanel')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});


Route::prefix('admin')->middleware(['adminpanel', 'checkadmin'])->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('admin.drivers');
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::post('/drivers/{id}/approve', [AdminController::class, 'approveDriver'])->name('admin.drivers.approve');
    Route::post('/drivers/{id}/suspend', [AdminController::class, 'suspendDriver'])->name('admin.drivers.suspend');
    Route::post('/drivers/{id}/block', [AdminController::class, 'blockDriver'])->name('admin.drivers.block');
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/loyalty', [AdminController::class, 'loyaltySettings'])->name('admin.loyalty');
    Route::post('/loyalty/update', [AdminController::class, 'updateLoyalty'])->name('admin.loyalty.update');
    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});



