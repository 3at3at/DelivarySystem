@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">📦 My Deliveries</h2>

<table class="table-auto w-full border border-gray-300 text-sm">
    <thead class="bg-gray-100">
        <tr>
            <th class="p-3 text-left">Pickup</th>
            <th class="p-3 text-left">Dropoff</th>
            <th class="p-3 text-left">Urgency</th>
            <th class="p-3 text-left">Status</th>
            <th class="p-3 text-left">Driver</th>
            <th class="p-3 text-left">Created At</th>
            <th class="p-3 text-left">Price</th>
            <th class="p-3 text-left">Payment</th>
            <th class="p-3 text-left">Chat</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($deliveries as $delivery)
            @php
                $symbol = match($delivery->currency) {
                    'USD' => '$',
                    'EUR' => '€',
                    'LBP' => 'ل.ل',
                    default => '',
                };

                $hasDiscount = $delivery->original_price && $delivery->original_price > $delivery->price;

                // Show fallback if conversion is missing
                $convertedToShow = $delivery->converted_price > 0
                    ? $delivery->converted_price
                    : $delivery->price;

                // Converted original for strikethrough (optional)
                $convertedOriginal = $delivery->original_price > 0
                    ? ($delivery->converted_price > 0 ? $delivery->original_price * ($delivery->converted_price / $delivery->price) : $delivery->original_price)
                    : null;
            @endphp

            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">{{ $delivery->pickup_location }}</td>
                <td class="p-3">{{ $delivery->dropoff_location }}</td>
                <td class="p-3">{{ ucfirst($delivery->urgency) }}</td>
                <td class="p-3 font-semibold">{{ ucfirst($delivery->status) }}</td>

                <td class="p-3">
                    @if($delivery->driver)
                        {{ $delivery->driver->name }}
                        <br>
                        <a href="{{ route('client.review.form', $delivery->id) }}"
                           class="text-blue-600 underline text-sm">Rate Driver</a>
                    @else
                        <span class="text-gray-400 italic">Unassigned</span>
                    @endif
                </td>

                <td class="p-3">{{ $delivery->created_at->format('Y-m-d H:i') }}</td>

                <td class="p-3">
                    @if ($hasDiscount && $convertedOriginal)
                        <span class="line-through text-gray-500 text-xs">
                            {{ $symbol }}{{ number_format($convertedOriginal, 2) }}
                        </span><br>
                        <span class="text-green-600 font-semibold">
                            {{ $symbol }}{{ number_format($convertedToShow, 2) }}
                        </span><br>
                        <span class="text-xs text-green-600">🎁 Discount Applied</span>
                    @else
                        <span class="text-black font-medium">
                            {{ $symbol }}{{ number_format($convertedToShow, 2) }}
                        </span>
                    @endif
                    <span class="text-xs text-gray-500">({{ $delivery->currency ?? 'LBP' }})</span><br>
                    <span class="text-xs text-gray-400">[Raw LBP: {{ number_format($delivery->price, 2) }}]</span>
                </td>

                <td class="p-3">
                    {{ strtoupper($delivery->payment_method) }}<br>
                    @if ($delivery->is_paid)
                        <span class="text-green-600 font-bold">Paid</span>
                    @else
                        <span class="text-red-600 font-bold">Unpaid</span>
                    @endif
                </td>

                <td class="p-3">
                    @if ($delivery->driver_id)
                        <a href="{{ route('client.chat', ['deliveryId' => $delivery->id]) }}"
                           class="inline-block px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                            💬 Chat with Driver
                        </a>
                    @else
                        <span class="text-muted">No Driver Yet</span>
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection
