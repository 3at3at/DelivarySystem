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
            'delivery_id' => 'required|exists:deliveries,id',
            'driver_id' => 'required|exists:drivers,id',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string',
        ]);

        Review::create([
            'client_id' => Auth::id(),
            'delivery_id' => $request->delivery_id, // Changed to delivery_id
            'driver_id' => $request->driver_id,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return back()->with('success', 'Review submitted!');
    }

    public function showReviewForm($id)
    {
        $delivery = \App\Models\Delivery::with('delivery')->findOrFail($id); // Changed to Delivery model

        if (!$delivery->driver_id) { // Changed order to delivery
            return redirect()->back()->with('error', 'No driver assigned yet.');
        }


        return view('client.reviews.create', compact('delivery')); // Updated to pass delivery
    }

    public function show($id)
    {
        $driver = Driver::with(['deliveries', 'reviews.client'])->findOrFail($id);

        $averageRating = $driver->reviews()->avg('rating');

        return view('client.drivers.show', compact('driver', 'averageRating'));
    }
}
