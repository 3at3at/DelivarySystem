<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Delivery;
use Illuminate\Http\Request;
use App\Mail\DriverAssignedMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\LoyaltySetting;

class AdminController extends Controller
{
    public function dashboard()
    {
        $activeDrivers = Driver::where('is_available', true)->count(); 
        $pendingDeliveries = Delivery::where('status', 'Pending')->count(); 

        return view('admin.dashboard', compact('activeDrivers', 'pendingDeliveries'));
    }

    public function drivers()
    {
        $drivers = Driver::all();
        return view('admin.drivers', compact('drivers'));
    }

    public function approveDriver($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = 'approved';
        $driver->save();
        return redirect()->back()->with('success', 'Driver approved.');
    }

    public function suspendDriver($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = 'suspended';
        $driver->save();
        return redirect()->back()->with('success', 'Driver suspended.');
    }

    public function blockDriver($id)
    {
        $driver = Driver::findOrFail($id);
        $driver->status = 'blocked';
        $driver->save();
        return redirect()->back()->with('success', 'Driver blocked successfully.');
    }

   public function deliveries(Request $request)
{
    $status = $request->query('status');

    $deliveries = Delivery::with(['client', 'driver'])
        ->when($status, function ($query) use ($status) {
            // Filter by selected status
            $query->where('status', $status);
        }, function ($query) {
            // If no status is selected, show all + rejected
            $query->where(function ($q) {
                $q->whereNotNull('status')
                  ->orWhere('driver_status', 'rejected');
            });
        })
        ->latest()
        ->get();

    $drivers = Driver::where('is_available', true)->get();

    return view('admin.orders', compact('deliveries', 'drivers', 'status'));
}


    public function assignDriver(Request $request, $id)
    {
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
        ]);

        $delivery = Delivery::findOrFail($id);
        $driver = Driver::findOrFail($request->driver_id);

        // 1. Check availability
        if (!$driver->is_available) {
            return back()->with('error', 'Driver is marked as unavailable.');
        }

        // 2. Check for conflict
        $conflict = Delivery::where('driver_id', $driver->id)
            ->where('scheduled_at', $delivery->scheduled_at)
            ->exists();

        if ($conflict) {
            return back()->with('error', 'Driver already has a delivery at this time.');
        }

        // 3. Check working hours
        if ($driver->working_hours) {
            $deliveryTime = Carbon::parse($delivery->scheduled_at)->format('H:i');
            $deliveryDay = Carbon::parse($delivery->scheduled_at)->format('l');

            $scheduleLines = explode("\n", $driver->working_hours);
            $workingMap = collect($scheduleLines)->mapWithKeys(function ($line) {
                [$day, $range] = explode(':', $line);
                return [trim($day) => trim($range)];
            });

            if (!isset($workingMap[$deliveryDay])) {
                return back()->with('error', "Driver doesn't work on {$deliveryDay}.");
            }

            [$startTime, $endTime] = explode('-', $workingMap[$deliveryDay]);
            $start = Carbon::parse($startTime)->format('H:i');
            $end = Carbon::parse($endTime)->format('H:i');

            if ($deliveryTime < $start || $deliveryTime > $end) {
                return back()->with('error', "Driver is not working at {$deliveryTime}.");
            }
        }

        // ✅ Assign
        $delivery->driver_id = $driver->id;
        $delivery->status = 'Accepted';
        $delivery->driver_status = 'pending';
        $delivery->save();

        // ✅ Notify driver
        if ($driver->email) {
            Mail::to($driver->email)->send(new DriverAssignedMail($delivery));
        }

        return back()->with('success', 'Driver assigned and notified!');
    }

    public function searchDrivers(Request $request)
    {
        $q = $request->query('q');

        $drivers = Driver::where('is_available', true)
            ->where('name', 'like', "%{$q}%")
            ->get(['id', 'name']);

        return response()->json($drivers);
    }

    public function loyaltySettings()
    {
        $setting = LoyaltySetting::first();
        return view('admin.loyalty', compact('setting'));
    }

    public function updateLoyalty(Request $request)
    {
        $request->validate([
            'points_per_km' => 'required|numeric|min:0.1',
            'bonus_threshold' => 'required|integer|min:10',
            'bonus_reward' => 'required|numeric|min:0',
        ]);

        $setting = LoyaltySetting::first() ?? new LoyaltySetting();
        $setting->points_per_km = $request->points_per_km;
        $setting->bonus_threshold = $request->bonus_threshold;
        $setting->bonus_reward = $request->bonus_reward;
        $setting->save();

        return back()->with('success', 'Loyalty settings updated!');
    }
}
