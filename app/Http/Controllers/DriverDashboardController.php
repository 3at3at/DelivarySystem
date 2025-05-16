<?php

namespace App\Http\Controllers;

use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Str;

class DriverDashboardController extends Controller
{
    public function index()
    {
        $driver = Auth::guard('driver')->user();
        $driverId = $driver->id;

        // 📊 Weekly deliveries grouped by day name
        $weeklyDeliveries = Delivery::where('driver_id', $driverId)
            ->whereBetween('scheduled_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->get()
            ->groupBy(fn ($d) => Carbon::parse($d->scheduled_at)->format('l'))
            ->map->count();

        $chartData = collect(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'])
            ->map(fn ($day) => $weeklyDeliveries[$day] ?? 0);

        // ✅ Deliveries Today
        $todayDeliveries = Delivery::where('driver_id', $driverId)
            ->whereDate('scheduled_at', today())
            ->count();

        // 💵 Earnings
        $completed = Delivery::where('driver_id', $driverId)
            ->where('status', 'Delivered')
            ->get();

        $totalEarnings = $completed->sum('price');
        $commission = $totalEarnings * 0.1;
        $netIncome = $totalEarnings - $commission;
        $recentDeliveries = $completed->sortByDesc('updated_at')->take(5);

        // 🕓 Calculate Online Hours for Today
        $today = now()->format('l'); // Example: "Thursday"
        $workingHours = $driver->working_hours ?? '';
        $lines = explode("\n", $workingHours);
        $onlineHours = 0;

        foreach ($lines as $line) {
            [$day, $timeRange] = array_pad(explode(':', $line, 2), 2, '');
            $day = trim($day);
            $timeRange = trim($timeRange);
            if ($day === $today && Str::contains($timeRange, '-')) {
                [$start, $end] = array_map('trim', explode('-', $timeRange));
                try {
                    $startTime = Carbon::createFromFormat('H:i', $start);
                    $endTime = Carbon::createFromFormat('H:i', $end);
                $onlineHours = max(0, $startTime->diffInHours($endTime));

                } catch (\Exception $e) {
                    $onlineHours = 0;
                }
                break;
            }
        }

        return view('drivers.dashboard', compact(
            'chartData',
            'todayDeliveries',
            'totalEarnings',
            'commission',
            'netIncome',
            'recentDeliveries',
            'onlineHours'
        ));
    }
}
