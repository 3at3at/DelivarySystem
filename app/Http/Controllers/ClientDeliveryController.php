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
        'scheduled_at'        => 'required|date',
        'driver_id'           => 'nullable|exists:drivers,id',

    ]);

    $scheduledAt = $request->scheduled_at;
    $driverId = $request->driver_id;
// Prevent assigning same driver to multiple deliveries at same time
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

    // Auto-assign if driver not selected
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

        $driverId = $availableDriver?->id ?? null; // Allow fallback to null
    }

    // Price Calculation
    $driver = $driverId ? Driver::find($driverId) : null;
    $calculatedPrice = 10000;

    if ($request->urgency === 'urgent') {
        $calculatedPrice += 1000000;
    }

    if ($driver) {
        if ($driver->pricing_model === 'fixed') {
            $calculatedPrice = $driver->price;
        } elseif ($driver->pricing_model === 'per_kg') {
            $calculatedPrice = $driver->price * $request->package_weight;
        }
    }
// Step: Currency Conversion based on user's preference
$user = \App\Models\User::find(Auth::id());

$preferredCurrency = strtoupper($user->preferred_currency ?? 'LBP');

$exchangeRates = [
    'USD' => 1 / 89500,
    'EUR' => 1 / 96000,
    'LBP' => 1,
];

$conversionRate = $exchangeRates[$preferredCurrency] ?? 1;
$calculatedPrice = $calculatedPrice > 0 ? $calculatedPrice : 10000;

// ✅ Store original BEFORE applying any discount
$originalPrice = $calculatedPrice;

$convertedPrice = round($calculatedPrice * $conversionRate, 6);

$distanceKm = $this->estimateDistance($request->pickup_location, $request->dropoff_location);

if ($user->has_bonus) {
    $loyalty = \App\Models\LoyaltySetting::first();
    $bonusPercent = $loyalty->bonus_reward ?? 0;

    $discountAmount = $originalPrice * ($bonusPercent / 100);
    $calculatedPrice = $originalPrice - $discountAmount;
    $convertedPrice = round($calculatedPrice * $conversionRate, 6); // apply to converted price too

    // reset bonus flag
   // $user->has_bonus = false;
   // $user->save();

    session()->flash('success', '🎁 Loyalty discount of ' . $bonusPercent . '% applied!');
}
/*dd([
    'has_bonus' => $user->has_bonus,
    'original_price' => $originalPrice,
    'discount_applied' => $calculatedPrice < $originalPrice,
    'calculated_price' => $calculatedPrice,
    'converted_price' => $convertedPrice,
]);*/

    // Save delivery even if driver is not assigned
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
      'distance_km' => $distanceKm, // ✅ this matches your DB column
       'original_price' => $originalPrice,

        'status'             => 'Pending',
     // Save real price in LBP
    'price'              => $calculatedPrice,

    // Save converted price and currency for display
 'converted_price' => (float) $convertedPrice,
'currency' => (string) $preferredCurrency,
'payment_method' => $request->payment_method,
'is_paid' => in_array($request->payment_method, ['cod']) ? false : true, // Pay later for COD



    ]);
    // Reset bonus AFTER successful delivery creation
if ($user->has_bonus) {
    $user->has_bonus = false;
    $user->save();
}


    return redirect()->route('orders.index')->with('success',
        $driverId ? 'Delivery created and driver assigned.' : 'Delivery created. Awaiting driver assignment.');
}

   public function calendar()
{
    $clientId = Auth::id();

    $deliveries = \App\Models\Delivery::where('client_id', $clientId)
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
    // Very basic heuristic: count differing characters between locations
    similar_text(strtolower($pickup), strtolower($dropoff), $percent);

    // The less similar the names, the longer the distance
    $distance = 100 - $percent;

    // Convert that difference percentage into a fake km value
    return round(max($distance / 5, 1), 2); // Minimum 1 km, max ~20 km
}

}
