@extends('layouts.app')

@section('content')
@php
    $isDriver = Auth::guard('driver')->check();
    $currentUserId = $isDriver ? Auth::guard('driver')->id() : Auth::id();
    $currentUserType = $isDriver ? 'driver' : 'client';
@endphp

<div class="max-w-3xl mx-auto mt-8 bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">💬 Live Chat </h2>

    <div class="bg-gray-100 p-4 h-80 overflow-y-scroll rounded mb-4">
   @foreach ($messages->reverse() as $msg)
    @php
        $isCurrentUser = (
            (Auth::guard('web')->check() && $msg->sender_type === \App\Models\User::class && $msg->sender_id === Auth::id()) ||
            (Auth::guard('driver')->check() && $msg->sender_type === \App\Models\Driver::class && $msg->sender_id === Auth::guard('driver')->id())
        );

        $alignment = $isCurrentUser ? 'text-right' : 'text-left';
        $bubbleColor = $isCurrentUser ? 'bg-blue-200' : 'bg-green-200';
        $displayType = class_basename($msg->sender_type); // "User" or "Driver"
    @endphp

    <div class="{{ $alignment }} mb-3">
        <div class="inline-block p-3 rounded-lg {{ $bubbleColor }}">
            <p class="text-sm font-semibold">
                {{ $displayType === 'User' ? 'Client' : 'Driver' }}: {{ $msg->sender?->name ?? 'Unknown' }}
            </p>
            <p>{{ $msg->message }}</p>
            <p class="text-xs text-gray-600">{{ $msg->created_at->diffForHumans() }}</p>
        </div>
    </div>
@endforeach




   <form method="POST" action="{{ $isDriver ? route('driver.chat.send') : route('client.chat.send') }}">
    @csrf
    <input type="hidden" name="delivery_id" value="{{ $delivery->id }}">  
    <textarea name="message" rows="3" class="w-full p-2 border rounded mb-3" placeholder="Type your message..." required></textarea>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">Send</button>
</form>

</div>
@endsection

@push('scripts')
<script>
    const chatBox = document.querySelector('.overflow-y-scroll');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>
@endpush

