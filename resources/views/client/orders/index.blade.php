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
        </tr>
    </thead>
    <tbody>
        @foreach ($deliveries as $delivery) <!-- Use $delivery instead of $order -->
        <tr class="border-t hover:bg-gray-50">
            <td class="p-3">{{ $delivery->pickup_location ?? '-' }}</td>
            <td class="p-3">{{ $delivery->dropoff_location ?? '-' }}</td>
            <td class="p-3">{{ ucfirst($delivery->urgency ?? '-') }}</td>
            <td class="p-3 font-semibold">
                {{ ucfirst($delivery->status ?? 'N/A') }}
            </td>

            <td class="p-3">
                @if($delivery->driver) <!-- Check if the delivery has a driver assigned -->
                    {{ $delivery->driver->name }}
                    <br>
                    <a href="{{ route('client.review.form', $delivery->id) }}" class="text-blue-600 underline text-sm">Rate Driver</a>
                @else
                    <span class="text-gray-400 italic">Unassigned</span>
                @endif
            </td>

            <td class="p-3">{{ $delivery->created_at->format('Y-m-d H:i') }}</td>

            <td>
                @if ($delivery->driver_id) <!-- Check if the driver_id exists -->
                    <a href="{{ route('client.chat', ['deliveryId' => $delivery->id]) }}" class="inline-block px-4 py-2 text-sm text-white bg-blue-600 rounded hover:bg-blue-700 transition">
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
