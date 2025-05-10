<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

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

        return back()->with('success', 'Delivery status updated.');
    }


public function calendar()
{
    $deliveries = Delivery::where('driver_id', Auth::guard('driver')->id())->get();

    $events = $deliveries->map(function ($delivery) {
        return [
            'title' => 'Delivery to ' . $delivery->dropoff_location,
            'start' => $delivery->scheduled_at,
            'url' => route('driver.deliveries'), // optional: link to task
        ];
    });

    return view('drivers.calendar', ['events' => $events]);
}
}