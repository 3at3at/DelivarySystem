<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Driver;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{

    public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'driver_id' => 'required|exists:drivers,id',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string'
    ]);

    Review::create([
        'client_id' => Auth::id(),
        'order_id' => $request->order_id,
        'driver_id' => $request->driver_id,
        'rating' => $request->rating,
        'review' => $request->review,
    ]);

    return back()->with('success', 'Review submitted!');
}
public function showReviewForm($id)
{
    $order = \App\Models\Order::with('delivery')->findOrFail($id);

    if (!$order->delivery || !$order->delivery->driver_id) {
        return redirect()->back()->with('error', 'No driver assigned yet.');
    }

    return view('client.reviews.create', compact('order'));
}

public function show($id)
{
    $driver = Driver::with(['deliveries', 'reviews.client'])->findOrFail($id);

    $averageRating = $driver->reviews()->avg('rating');

    return view('client.drivers.show', compact('driver', 'averageRating'));
}


}
