@extends('layouts.driver')

@section('content')
<div class="container mt-4">
    <h4 class="mb-4">My Profile</h4>

    <div class="card shadow-sm">
        <div class="card-body">
            <ul class="list-group">
                <li class="list-group-item"><strong>Name:</strong> {{ $driver->name }}</li>
                <li class="list-group-item"><strong>Email:</strong> {{ $driver->email }}</li>
                <li class="list-group-item"><strong>Phone:</strong> {{ $driver->phone }}</li>
                <li class="list-group-item"><strong>Vehicle Type:</strong> {{ $driver->vehicle_type }}</li>
                <li class="list-group-item"><strong>Plate Number:</strong> {{ $driver->plate_number }}</li>
                <li class="list-group-item"><strong>Pricing Model:</strong> {{ ucfirst($driver->pricing_model) }}</li>
                <li class="list-group-item"><strong>Price:</strong> ${{ $driver->price }}</li>
                <li class="list-group-item"><strong>Location:</strong> {{ $driver->location ?? 'Not set' }}</li>
                <li class="list-group-item"><strong>Availability:</strong> {{ $driver->is_available ? 'Yes' : 'No' }}</li>
                <li class="list-group-item"><strong>Working Hours:</strong><br><pre>{{ $driver->working_hours }}</pre></li>
            </ul>
        </div>
    </div>
</div>
@endsection
