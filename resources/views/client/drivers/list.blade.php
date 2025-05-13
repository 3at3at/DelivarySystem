@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Available Drivers</h2>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    @foreach ($drivers as $driver)
        <div class="border p-4 rounded shadow">
            <h3 class="text-lg font-semibold">{{ $driver->name }}</h3>
            <p>Rating: {{ $driver->rating ?? 'N/A' }}</p>
            <p>Area: {{ $driver->area ?? 'Unknown' }}</p>
            <a href="{{ route('client.drivers.show', $driver->id) }}" class="text-blue-600 underline mt-2 inline-block">View Profile</a>
        </div>
    @endforeach
</div>
@endsection
