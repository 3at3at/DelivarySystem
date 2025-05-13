<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class MessageController extends Controller
{
public function index($orderId)
{
    $order = Order::with('delivery')->findOrFail($orderId);

    // ✅ Fix: Separate guard checks to avoid OR bug
    if (Auth::guard('web')->check()) {
        if ($order->client_id !== Auth::id()) {
            abort(403, 'Unauthorized access to this chat.');
        }
    } elseif (Auth::guard('driver')->check()) {
        if (!$order->delivery || $order->delivery->driver_id !== Auth::guard('driver')->id()) {
            abort(403, 'Unauthorized access to this chat.');
        }
    } else {
        // No one logged in
        abort(403, 'You must be logged in.');
    }

    $messages = Message::where('order_id', $orderId)->latest()->get();

    return view('client.chat.index', [
        'messages' => $messages,
        'orderId' => $orderId,
    ]);
}






public function store(Request $request)
{
    $request->validate([
        'order_id' => 'required|exists:orders,id',
        'message' => 'required|string|max:1000',
    ]);

    if (Auth::guard('driver')->check()) {
        $senderType = 'driver';
        $senderId = Auth::guard('driver')->id();
    } elseif (Auth::guard('web')->check()) {
        $senderType = 'client';
        $senderId = Auth::id();
    } else {
        abort(403, 'Unauthorized sender.');
    }

    Message::create([
        'order_id' => $request->order_id,
        'sender_id' => $senderId,
        'sender_type' => $senderType,
        'message' => $request->message,
    ]);

    return back();
}


}
