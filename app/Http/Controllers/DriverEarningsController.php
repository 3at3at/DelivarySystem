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
        $commissionRate = 0.10;
        $totalCommission = $totalRevenue * $commissionRate;
        $netEarnings = $totalRevenue - $totalCommission;

        return view('drivers.earnings.index', compact(
            'completedDeliveries',
            'totalRevenue',
            'totalCommission',
            'netEarnings'
        ));
    }
}
