@extends('layouts.driver')

@section('content')
<div class="alert alert-info text-center">
    Welcome, <strong>{{ auth()->guard('driver')->user()->name }}</strong>! You are logged in as a <strong>Driver</strong>.
</div>

<div class="row">
    <div class="col-md-8 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-chart-line"></i> Delivery Stats</div>
            <div class="card-body">
                <canvas id="deliveryChart"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-4">
        <div class="card shadow-sm">
            <div class="card-header"><i class="fas fa-info-circle"></i> Quick Stats</div>
            <div class="card-body">
                <p><strong>Deliveries Today:</strong> {{ $todayDeliveries ?? 0 }}</p>
                <p><strong>Total Earnings:</strong> ${{ $totalEarnings ?? '0.00' }}</p>
                <p><strong>Online Hours:</strong> {{ $onlineHours ?? '0 hrs' }}</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-truck"></i> Deliveries</div>
            <div class="card-body">
                <p>View your current deliveries and update their status.</p>
                <a href="{{ route('driver.deliveries') }}" class="btn btn-custom">Manage Deliveries</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-clock"></i> Availability</div>
            <div class="card-body">
                <p>Set your available hours and working location.</p>
                <a href="{{ route('driver.availability') }}" class="btn btn-custom">Set Availability</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-calendar"></i> Delivery Calendar</div>
            <div class="card-body">
                <p>View your delivery schedule on the calendar.</p>
                <a href="{{ route('driver.calendar') }}" class="btn btn-custom">View Calendar</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-dollar-sign"></i> Earnings</div>
            <div class="card-body">
                <p>Track your revenue and commission details.</p>
                <a href="{{ route('driver.earnings') }}" class="btn btn-custom">View Earnings</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('deliveryChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'Deliveries',
                data: {!! json_encode($weeklyDeliveries ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                backgroundColor: '#4a90e2'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
@endsection
