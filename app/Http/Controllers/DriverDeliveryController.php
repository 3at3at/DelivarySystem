<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use App\Models\User;
use App\Models\LoyaltySetting;

class DriverDeliveryController extends Controller
{
    public function index()
    {
        $deliveries = Delivery::where('driver_id', Auth::guard('driver')->id())
            ->with('client')
            ->get();

        return view('drivers.deliveries.index', compact('deliveries'));
    }

    public function updateStatus(Request $request, $id)
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver->is_available) {
            return back()->with('error', '❌ You are currently unavailable and cannot update delivery status.');
        }

        $delivery = Delivery::where('driver_id', $driver->id)->findOrFail($id);
        $delivery->status = $request->status;
        $delivery->save();

        if ($request->status === 'Delivered') {
            $loyalty = LoyaltySetting::first();
            $client = User::find($delivery->client_id);

            if ($client && $loyalty) {
                $km = $delivery->distance_km ?? 0;
                $earnedPoints = $km * $loyalty->points_per_km;

                $client->points += $earnedPoints;

                if ($client->points >= $loyalty->bonus_threshold) {
                    $client->points = 0;
                    $client->has_bonus = true;
                    $client->save();

                    return view('drivers.bonus-earned', [
                        'clientName' => $client->name,
                        'reward' => $loyalty->bonus_reward
                    ]);
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
        $driver = Auth::guard('driver')->user();

        if (!$driver->is_available) {
            return back()->with('error', '❌ You are currently unavailable and cannot accept deliveries.');
        }

        $hasActive = Delivery::where('driver_id', $driver->id)
            ->where('driver_status', 'accepted')
            ->where('status', 'In Progress')
            ->exists();

        if ($hasActive) {
            return back()->with('error', '🚫 You already have a delivery in progress. Complete it first.');
        }

        $delivery = Delivery::where('driver_id', $driver->id)
            ->where('id', $id)
            ->where('driver_status', 'pending')
            ->firstOrFail();

        $delivery->driver_status = 'accepted';
        $delivery->status = 'Accepted';
        $delivery->save();

        return back()->with('success', '✅ Delivery accepted.');
    }

    public function reject($id)
    {
        $driver = Auth::guard('driver')->user();

        if (!$driver->is_available) {
            return back()->with('error', '❌ You are currently unavailable and cannot reject deliveries.');
        }

        $delivery = Delivery::where('driver_id', $driver->id)->findOrFail($id);

        $delivery->driver_status = 'rejected';
        $delivery->status = 'Pending';
        $delivery->driver_id = null;
        $delivery->save();

        return back()->with('success', '🛑 Delivery rejected.');
    }
}
