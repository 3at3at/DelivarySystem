<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\LoyaltySetting;

class DriverDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::where('driver_id', Auth::guard('driver')->id())->get();
        return view('drivers.deliveries.index', compact('deliveries'));
    }

    public function updateStatus(Request $request, $id)
{
    $delivery = Delivery::where('driver_id', Auth::guard('driver')->id())->findOrFail($id);
    $delivery->status = $request->status;
    $delivery->save();

    // ✅ Loyalty Program on Delivery Completion
    if ($request->status === 'Delivered') {
        $loyalty = LoyaltySetting::first();
        $client = $delivery->client;

        if ($client && $loyalty) {
            $km = $delivery->distance_km ?? 0; // Make sure this column exists
            $earnedPoints = $km * $loyalty->points_per_km;

            // 👉 Dump values to check what’s happening
            dd([
                'distance_km' => $km,
                'points_per_km' => $loyalty->points_per_km,
                'earned_points' => $earnedPoints,
                'client_points_before' => $client->points,
            ]);

            $client->points += $earnedPoints;

            if ($client->points >= $loyalty->bonus_threshold) {
                session()->flash('bonus_earned', '🎉 Client earned a loyalty reward!');
                $client->points = 0; // reset points after reward
            }

            $client->save();
        }
    }

    return back()->with('success', 'Delivery status updated.');
}


    public function calendar()
    {
        $deliveries = Delivery::where('driver_id', Auth::guard('driver')->id())->get();

        $events = $deliveries->map(function ($delivery) {
            return [
                'title' => 'Delivery to ' . $delivery->dropoff_location,
                'start' => $delivery->scheduled_at,
                'url' => route('driver.deliveries'),
            ];
        });

        return view('drivers.calendar', ['events' => $events]);
    }


public function accept($id)
{
    $driverId = Auth::guard('driver')->id();

    // Check if the driver already has an active delivery
    $hasActive = \App\Models\Delivery::where('driver_id', $driverId)
        ->where('driver_status', 'accepted')
        ->where('status', 'In Progress')
        ->exists();

    if ($hasActive) {
        return back()->with('error', '🚫 You already have a delivery in progress. Complete it before accepting a new one.');
    }

    $delivery = \App\Models\Delivery::where('driver_id', $driverId)
        ->where('id', $id)
        ->where('driver_status', 'pending')
        ->firstOrFail();

    $delivery->driver_status = 'accepted';
    $delivery->status = 'In Progress';
    $delivery->save();

    return back()->with('success', '✅ Delivery accepted and marked as In Progress.');
}



   public function reject($id)
{
    $driver = Auth::guard('driver')->user();

    // Check if the driver is available before proceeding
    if (!$driver->is_available) {
        return back()->with('error', 'You are currently not available to reject deliveries.');
    }

    // Find the delivery assigned to this driver
    $delivery = Delivery::where('driver_id', $driver->id)->findOrFail($id);

    // Update delivery status and remove assignment
    $delivery->driver_status = 'rejected';
    $delivery->status = 'Pending';
    $delivery->driver_id = null;
    $delivery->save();

    return back()->with('success', 'Delivery rejected.');
}

}
