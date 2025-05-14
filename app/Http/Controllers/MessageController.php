<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Delivery;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
   public function index($deliveryId)
{
    // Fetch the delivery along with the client and driver
    $delivery = Delivery::with('client', 'driver')->findOrFail($deliveryId);

    // Check if the current authenticated user is allowed to access the chat
    if (Auth::guard('web')->check()) {
        if ($delivery->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this chat.');
        }
    } elseif (Auth::guard('driver')->check()) {
        if ($delivery->driver_id !== Auth::guard('driver')->id()) {
            abort(403, 'Unauthorized access to this chat.');
        }
    } else {
        abort(403, 'You must be logged in.');
    }

    // Fetch messages related to the delivery
    $messages = Message::where('delivery_id', $deliveryId)->latest()->get();



    // Pass the delivery object to the view as well
    return view('client.chat.index', [
        'messages' => $messages,
        'delivery' => $delivery, // Add the delivery object here
    ]);
}


    public function store(Request $request)
    {
        $request->validate([
            'delivery_id' => 'required|exists:deliveries,id',
            'message' => 'required|string|max:1000',
        ]);

      if (Auth::guard('driver')->check()) {
    $senderType = \App\Models\Driver::class;
    $senderId = Auth::guard('driver')->id();
} elseif (Auth::guard('web')->check()) {
    $senderType = \App\Models\User::class;
    $senderId = Auth::id();
}


        Message::create([
            'delivery_id' => $request->delivery_id,
            'sender_id' => $senderId,
            'sender_type' => $senderType,
            'message' => $request->message,
        ]);

        return back();
    }
}
