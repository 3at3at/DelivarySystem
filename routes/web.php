<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\DriverAuthController;
use App\Http\Controllers\DriverDeliveryController;
use App\Http\Controllers\DriverAvailabilityController;
use App\Http\Controllers\DriverEarningsController;
use App\Http\Controllers\DriverDashboardController;

use App\Http\Controllers\ClientDeliveryController;
use App\Http\Controllers\ClientDriverController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ClientAuthController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\MessageController;

Route::get('/', fn () => view('welcome'));

Route::get('/login', fn () => redirect()->route('driver.login'))->name('login');

// 🚚 DRIVER AUTH
Route::prefix('driver')->group(function () {
    Route::get('/register', [DriverAuthController::class, 'showRegisterForm'])->name('driver.register');
    Route::post('/register', [DriverAuthController::class, 'register']);
    Route::get('/login', [DriverAuthController::class, 'showLoginForm'])->name('driver.login');
    Route::post('/login', [DriverAuthController::class, 'login'])->name('driver.login.submit');
});

// 🔐 DRIVER AUTHENTICATED ROUTES
Route::middleware('auth:driver')->prefix('driver')->group(function () {
    Route::get('/dashboard', [DriverDashboardController::class, 'index'])->name('driver.dashboard');
    Route::post('/logout', [DriverAuthController::class, 'logout'])->name('driver.logout');

    Route::get('/deliveries', [DriverDeliveryController::class, 'index'])->name('driver.deliveries');
    Route::post('/deliveries/{id}/update-status', [DriverDeliveryController::class, 'updateStatus'])->name('driver.delivery.update');

    Route::get('/availability', [DriverAvailabilityController::class, 'edit'])->name('driver.availability');
    Route::post('/availability', [DriverAvailabilityController::class, 'update']);

    Route::get('/calendar', [DriverDeliveryController::class, 'calendar'])->name('driver.calendar');
    Route::get('/earnings', [DriverEarningsController::class, 'index'])->name('driver.earnings');

    Route::post('/deliveries/{id}/accept', [DriverDeliveryController::class, 'accept'])->name('driver.deliveries.accept');
    Route::post('/deliveries/{id}/reject', [DriverDeliveryController::class, 'reject'])->name('driver.deliveries.reject');

    Route::get('/profile', fn () => view('drivers.profile', ['driver' => Auth::guard('driver')->user()]))->name('driver.profile');
    Route::post('/save-token', [DriverAuthController::class, 'saveToken']);
});

// 🧑‍💼 ADMIN ROUTES
Route::prefix('admin')->middleware('adminpanel')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');
});

Route::prefix('admin')->middleware(['adminpanel', 'checkadmin'])->group(function () {
    Route::post('/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/drivers', [AdminController::class, 'drivers'])->name('admin.drivers');

    Route::post('/drivers/{id}/approve', [AdminController::class, 'approveDriver'])->name('admin.drivers.approve');
    Route::post('/drivers/{id}/suspend', [AdminController::class, 'suspendDriver'])->name('admin.drivers.suspend');
    Route::post('/drivers/{id}/block', [AdminController::class, 'blockDriver'])->name('admin.drivers.block');

    Route::get('/deliveries', [AdminController::class, 'deliveries'])->name('admin.deliveries');
    Route::post('/deliveries/{id}/assign', [AdminController::class, 'assignDriver'])->name('admin.deliveries.assign');

    Route::get('/drivers/search', [AdminController::class, 'searchDrivers'])->name('admin.drivers.search');

    Route::get('/loyalty', [AdminController::class, 'loyalty'])->name('admin.loyalty');

    Route::post('/loyalty/update', [AdminController::class, 'updateLoyalty'])->name('admin.loyalty.update');

    Route::get('/reports', [AdminController::class, 'reports'])->name('admin.reports');
});

// 👤 CLIENT ROUTES
Route::prefix('client')->group(function () {
    Route::get('/register', [ClientAuthController::class, 'showRegisterForm'])->name('client.register');
    Route::post('/register', [ClientAuthController::class, 'register']);

    Route::get('/login', [ClientAuthController::class, 'showLoginForm'])->name('client.login');
    Route::post('/login', [ClientAuthController::class, 'login']);
    Route::post('/logout', [ClientAuthController::class, 'logout'])->name('client.logout');
});

Route::middleware(['client'])->prefix('client')->group(function () {
    Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('client.dashboard');

    Route::get('/deliveries/create', [ClientDeliveryController::class, 'create'])->name('client.deliveries.create');
    Route::post('/deliveries/store', [ClientDeliveryController::class, 'store'])->name('client.deliveries.store');
    Route::get('/deliveries', [ClientDeliveryController::class, 'index'])->name('orders.index');

    Route::get('/drivers', [ClientDriverController::class, 'list'])->name('client.drivers.list');
    Route::get('/drivers/{id}', [ClientDriverController::class, 'show'])->name('client.drivers.show');

    Route::post('/reviews/store', [ReviewController::class, 'store'])->name('client.reviews.store');
    Route::get('/deliveries/{id}/review', [ReviewController::class, 'showReviewForm'])->name('client.review.form');
});

// 💬 CHAT
Route::middleware(['auth:web'])->group(function () {
    Route::get('/client/chat/{deliveryId}', [MessageController::class, 'index'])->name('client.chat');
    Route::post('/client/chat/send', [MessageController::class, 'store'])->name('client.chat.send');
});

Route::middleware(['auth:driver'])->group(function () {
    Route::get('/driver/chat/{deliveryId}', [MessageController::class, 'index'])->name('driver.chat');
    Route::post('/driver/chat/send', [MessageController::class, 'store'])->name('driver.chat.send');
});

Route::get('/client/calendar', [ClientDeliveryController::class, 'calendar'])->name('client.calendar');

