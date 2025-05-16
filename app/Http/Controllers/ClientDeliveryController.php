<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Delivery;
use App\Models\Driver;
use App\Models\User;
use App\Models\LoyaltySetting;

class ClientDeliveryController extends Controller
{
    public function create(Request $request)
    {
        $drivers = Driver::where('is_available', true)->get();
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
            'scheduled_at'        => 'required|date',
            'driver_id'           => 'nullable|exists:drivers,id',
        ]);

        $scheduledAt = $request->scheduled_at;
        $driverId = $request->driver_id;

        if ($driverId) {
            $conflict = Delivery::where('scheduled_at', $scheduledAt)
                ->where('driver_id', $driverId)
                ->exists();

            if ($conflict) {
                return back()->withErrors([
                    'driver_id' => 'This driver is already booked at the selected time. Please choose another time or let the system auto-assign.',
                ])->withInput();
            }
        }

        if (!$driverId) {
            $driversInArea = Driver::whereRaw('LOWER(location) LIKE ?', ['%' . strtolower($request->pickup_location) . '%'])
                ->where('is_available', 1)
                ->pluck('id')
                ->toArray();

            $busyDrivers = Delivery::where('scheduled_at', $scheduledAt)
                ->whereIn('driver_id', $driversInArea)
                ->pluck('driver_id')
                ->toArray();

            $availableDriver = Driver::whereIn('id', $driversInArea)
                ->whereNotIn('id', $busyDrivers)
                ->inRandomOrder()
                ->first();

            $driverId = $availableDriver?->id ?? null;
        }

        $driver = $driverId ? Driver::find($driverId) : null;
        $distanceKm = $this->estimateDistance($request->pickup_location, $request->dropoff_location);

        $usdPrice = 10; // fallback USD price

        if ($driver) {
            if ($driver->pricing_model === 'fixed') {
                $usdPrice = $driver->price / 90000; // stored in LBP
            } elseif ($driver->pricing_model === 'per_kg') {
                $usdPrice = $distanceKm * $request->package_weight * ($driver->price / 90000);
            }
        }

        $usdPrice = max($usdPrice, 1.5);

        if ($request->urgency === 'urgent') {
            $usdPrice += 12;
        }

        $user = User::find(Auth::id());

        $preferredCurrency = strtoupper($user->preferred_currency ?? 'LBP');

        $exchangeRates = [
            'USD' => 1,
            'EUR' => 0.93,
            'LBP' => 90000,
        ];

        $conversionRate = $exchangeRates[$preferredCurrency] ?? 1;

        $originalPrice = $usdPrice;
        $convertedPrice = round($usdPrice * $conversionRate, 2);
        $finalUsdPrice = $usdPrice;
        $finalConvertedPrice = $convertedPrice;
        $discountApplied = false;

        if ($user->has_bonus) {
            $loyalty = LoyaltySetting::first();
            $bonusPercent = $loyalty->bonus_reward ?? 0;

            $finalUsdPrice = $originalPrice * (1 - $bonusPercent / 100);
            $finalConvertedPrice = round($finalUsdPrice * $conversionRate, 2);
            $discountApplied = true;

            session()->flash('success', '🎁 Loyalty discount of ' . $bonusPercent . '% applied!');
        }

        Delivery::create([
            'client_id'          => Auth::id(),
            'driver_id'          => $driverId,
            'pickup_location'    => $request->pickup_location,
            'dropoff_location'   => $request->dropoff_location,
            'package_type'       => $request->package_type,
            'package_weight'     => $request->package_weight,
            'package_dimensions' => $request->package_dimensions,
            'urgency'            => $request->urgency,
            'scheduled_at'       => $scheduledAt,
            'distance_km'        => $distanceKm,
            'original_price'     => $originalPrice * $exchangeRates['LBP'],
            'price'              => $finalUsdPrice * $exchangeRates['LBP'],
            'converted_price'    => $finalConvertedPrice,
            'currency'           => $preferredCurrency,
            'payment_method'     => $request->payment_method,
            'is_paid'            => in_array($request->payment_method, ['cod']) ? false : true,
            'status'             => 'Pending',
        ]);

        if ($discountApplied) {
            $user->has_bonus = false;
            $user->save();
        }

        return redirect()->route('orders.index')->with('success',
            $driverId ? 'Delivery created and driver assigned.' : 'Delivery created. Awaiting driver assignment.');
    }

    public function calendar()
    {
        $clientId = Auth::id();

        $deliveries = Delivery::where('client_id', $clientId)
            ->where('status', '!=', 'cancelled')
            ->get();

        $events = $deliveries->map(function ($delivery) {
            return [
                'title' => 'To: ' . $delivery->dropoff_location,
                'start' => $delivery->scheduled_at,
                'end' => $delivery->scheduled_at,
            ];
        });

        return view('client.calendar', ['events' => $events]);
    }

    private function estimateDistance($pickup, $dropoff): float
    {
        similar_text(strtolower($pickup), strtolower($dropoff), $percent);
        $distance = 100 - $percent;
        return round(max($distance / 5, 1), 2);
    }
}
