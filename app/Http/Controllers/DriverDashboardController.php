<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $driverId = Auth::guard('driver')->id();

        // Weekly delivery count
        $weeklyDeliveries = Delivery::where('driver_id', $driverId)
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->groupBy(fn ($d) => Carbon::parse($d->scheduled_at)->format('l'))
            ->map->count();

        $chartData = collect([
            'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'
        ])->map(fn ($day) => $weeklyDeliveries[$day] ?? 0);

        // Earnings
        $completed = Delivery::where('driver_id', $driverId)->where('status', 'Delivered')->get();
        $totalEarnings = $completed->sum('price');
        $commission = $totalEarnings * 0.1;
        $netIncome = $totalEarnings - $commission;
        $recentDeliveries = $completed->sortByDesc('updated_at')->take(5);

        return view('drivers.dashboard', compact('chartData', 'totalEarnings', 'commission', 'netIncome', 'recentDeliveries'));
    }
}
