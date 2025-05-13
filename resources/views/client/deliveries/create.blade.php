@extends('layouts.app')

@section('content')
<h2 class="text-2xl font-bold mb-4">Create Delivery</h2>
@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


<form method="POST" action="{{ route('client.deliveries.store') }}">
    @csrf

    <input type="text" name="pickup_location" placeholder="Pickup Location" class="w-full border p-2 rounded mb-3" required>
    <input type="text" name="dropoff_location" placeholder="Dropoff Location" class="w-full border p-2 rounded mb-3" required>
   <input type="text" name="package_type" placeholder="Package Type (e.g., Box, Envelope)" class="w-full border p-2 rounded mb-3" required>

<input type="number" step="0.1" name="package_weight" placeholder="Package Weight in kg" class="w-full border p-2 rounded mb-3" required>

<input type="text" name="package_dimensions" placeholder="Dimensions (LxWxH)" class="w-full border p-2 rounded mb-3">



    <select name="urgency" class="w-full border p-2 rounded mb-3" required>
        <option value="normal">Normal</option>
        <option value="urgent">Urgent</option>
    </select>

    <select name="driver_id" class="w-full border p-2 rounded mb-3">
        <option value="">Auto-Assign Driver</option>
        @foreach ($drivers as $driver)
           <option value="{{ $driver->id }}"
    {{ isset($selectedDriver) && $selectedDriver == $driver->id ? 'selected' : '' }}>
    {{ $driver->name }}
</option>

        @endforeach
    </select>

    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
</form>
@endsection
