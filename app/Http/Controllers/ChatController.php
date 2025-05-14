<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($deliveryId)
    {
        $delivery = Delivery::with('client', 'driver')->findOrFail($deliveryId);

        // Access control check
        $isClient = Auth::check() && Auth::id() === $delivery->client_id;
        $isDriver = Auth::guard('driver')->check() && Auth::guard('driver')->id() === $delivery->driver_id;

        if (!($isClient || $isDriver)) {
            abort(403, 'Unauthorized');
        }

        $messages = Message::where('delivery_id', $deliveryId)->orderBy('created_at')->get();

        return view('chat.index', compact('delivery', 'messages'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'message' => 'required|string|max:1000'
        ]);

        Message::create([
            'delivery_id' => $request->delivery_id,
            'sender_id' => Auth::id() ?? Auth::guard('driver')->id(),
            'sender_type' => Auth::guard('driver')->check() ? 'driver' : 'client',
            'message' => $request->message,
        ]);

        return back();
    }
}
