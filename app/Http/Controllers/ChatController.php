<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
class ChatController extends Controller
{
  public function index($orderId)
{
    $order = Order::with('delivery')->findOrFail($orderId);

    // Ensure logged-in user is related to this order
    if (
        Auth::user()->id !== $order->client_id &&
        Auth::guard('driver')->id() !== optional($order->delivery)->driver_id
    ) {
        abort(403, 'Unauthorized');
    }

    $messages = Message::where('order_id', $orderId)->orderBy('created_at')->get();

    return view('chat.index', compact('order', 'messages'));
}


    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'message' => 'required|string|max:1000'
        ]);

        Message::create([
            'order_id' => $request->order_id,
            'sender_id' => Auth::id(),
            'sender_type' => Auth::guard('driver')->check() ? 'driver' : 'client',
            'message' => $request->message,
        ]);

        return back();
    }


}
