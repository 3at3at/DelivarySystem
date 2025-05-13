@extends('layouts.app')

@section('content')
    <div class="max-w-xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-4">{{ $driver->name }}'s Profile</h2>

        <p><strong>Email:</strong> {{ $driver->email }}</p>
        <p><strong>Phone:</strong> {{ $driver->phone }}</p>
        <p><strong>Location:</strong> {{ $driver->location ?? 'Unknown' }}</p>
        <p><strong>Vehicle Type:</strong> {{ $driver->vehicle_type }}</p>
        <p><strong>Plate Number:</strong> {{ $driver->plate_number }}</p>
        <p><strong>Pricing Model:</strong> {{ ucfirst($driver->pricing_model) }}</p>
        <p><strong>Price:</strong> ${{ $driver->price }}</p>
        <p><strong>Working Hours:</strong> {{ $driver->working_hours }} hours/day</p>
        <p><strong>Status:</strong> {{ ucfirst($driver->status) }}</p>
        <p><strong>Total Deliveries:</strong> {{ $driver->deliveries_count ?? 0 }}</p>
         <p><strong>Average Rating:</strong>
        @if ($averageRating)
            {{ number_format($averageRating, 1) }} / 5 ★
        @else
            N/A
        @endif
    </p>
</div>

   @if ($driver->reviews->count())
    <h3 class="text-lg font-semibold mb-2">Client Reviews:</h3>
    <ul class="space-y-3">
        @foreach ($driver->reviews as $review)
            <li class="border p-3 rounded bg-gray-50">
                <div><strong>Rating:</strong> {{ $review->rating }} ★</div>
                @if ($review->review)
                    <div><em>"{{ $review->review }}"</em></div>
                @endif
                <div class="text-sm text-gray-500">By: {{ $review->client->name ?? 'Client' }}</div>
            </li>
        @endforeach
    </ul>
    <a href="{{ route('client.deliveries.create', ['selected_driver' => $driver->id]) }}"
   class="bg-blue-600 text-white px-4 py-2 rounded">
   Select This Driver
</a>

@endif
@endsection
