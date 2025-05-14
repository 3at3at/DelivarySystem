<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use App\Models\Driver;

class ClientDeliveryController extends Controller
{
    public function create(Request $request)
    {
        $drivers = Driver::all();
        $selectedDriver = $request->selected_driver;
        return view('client.deliveries.create', compact('drivers', 'selectedDriver'));
    }

    public function index()
    {
        $deliveries = Delivery::with('driver')
            ->where('client_id', Auth::id())
            ->latest()
            ->get();

        return view('client.orders.index', compact('deliveries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'pickup_location'     => 'required|string|min:2|max:100',
            'dropoff_location'    => 'required|string|min:2|max:100|different:pickup_location',
            'package_type'        => 'required|string|in:box,envelope,crate,pallet',
            'package_weight'      => 'required|numeric|min:0.1|max:1000',
            'package_dimensions'  => ['nullable', 'regex:/^\d{1,3}x\d{1,3}x\d{1,3}$/'],
            'urgency'             => 'required|in:normal,urgent',
            'driver_id'           => 'nullable|exists:drivers,id',
        ]);

        // Auto-assign driver by pickup area if not selected
        $driverId = $request->driver_id;
        if (!$driverId) {
            $driver = Driver::where('location', 'LIKE', '%' . $request->pickup_location . '%')
                ->where('is_available', 1)
                ->inRandomOrder()
                ->first();

            $driverId = $driver?->id;
        }

        // Price Calculation Logic
        $driver = Driver::find($driverId);
        $calculatedPrice = 10000; // default base

        if ($request->urgency === 'urgent') {
            $calculatedPrice += 5000;
        }

        if ($driver) {
            if ($driver->pricing_model === 'fixed') {
                $calculatedPrice = $driver->price;
            } elseif ($driver->pricing_model === 'per_kg') {
                $calculatedPrice = $driver->price * $request->package_weight;
            }
        }

        // Save the delivery
        $delivery = Delivery::create([
            'client_id'         => Auth::id(),
            'driver_id'         => $driverId,
            'pickup_location'   => $request->pickup_location,
            'dropoff_location'  => $request->dropoff_location,
            'package_type'      => $request->package_type,
            'package_weight'    => $request->package_weight,
            'package_dimensions'=> $request->package_dimensions,
            'urgency'           => $request->urgency,
            'status'            => 'Pending',
            'price'             => $calculatedPrice,
        ]);

        return redirect()->route('orders.index')->with('success', 'Delivery request created.');
    }
}
