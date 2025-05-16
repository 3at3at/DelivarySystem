<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class DriverEarningsController extends Controller
{
public function index()
{
    $driverId = Auth::guard('driver')->id();

    $completedDeliveries = Delivery::where('driver_id', $driverId)
        ->where('status', 'Delivered')
        ->get();

    $totalRevenue = $completedDeliveries->sum('price');

    // Only take last 5 unique completed deliveries ordered by schedule
    $recentDeliveries = $completedDeliveries
        ->sortByDesc('scheduled_at')
        ->unique(fn($item) => $item->pickup_location . $item->dropoff_location . $item->scheduled_at)
        ->take(5);

    return view('drivers.earnings.index', compact(
        'completedDeliveries',
        'recentDeliveries',
        'totalRevenue'
    ));
}

}
