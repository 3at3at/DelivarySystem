@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto mt-8">
    <!-- Profile Card -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-2xl font-bold text-blue-600 mb-4">👋 Welcome, {{ Auth::user()->name }}</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <p class="text-gray-700"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                <p class="text-gray-700"><strong>Email:</strong> {{ Auth::user()->email }}</p>
            </div>
            <div>
                <p class="text-gray-700"><strong>Phone:</strong> {{ Auth::user()->phone ?? '-' }}</p>
                <p class="text-gray-700"><strong>Preferred Currency:</strong> {{ Auth::user()->preferred_currency ?? 'LBP' }}</p>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex flex-col md:flex-row gap-4 justify-center mb-6">
        <a href="{{ route('client.deliveries.create') }}" class="bg-blue-600 text-white text-center px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            ➕ Create New Delivery
        </a>
        <a href="{{ route('orders.index') }}" class="bg-purple-600 text-white text-center px-6 py-3 rounded-lg shadow hover:bg-purple-700 transition">
            📦 My Deliveries
        </a>
        <a href="{{ route('client.drivers.list') }}" class="bg-green-600 text-white text-center px-6 py-3 rounded-lg shadow hover:bg-green-700 transition">
            👨‍✈️ View Available Drivers
        </a>
    </div>

    <!-- Optional Section: Short Stats (Future) -->
    <!-- <div class="bg-white shadow p-4 rounded">
        <p>Total Deliveries: ...</p>
        <p>Unpaid Orders: ...</p>
    </div> -->
</div>
@endsection
