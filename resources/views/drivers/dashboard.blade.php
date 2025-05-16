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
                <p><strong>Total Earnings:</strong> ${{ number_format($totalEarnings ?? 0, 2) }}</p>
                <p><strong>Online Hours:</strong> {{ $onlineHours ?? '0' }} hours</p>
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
                <a href="{{ route('driver.deliveries') }}" class="btn btn-primary">Manage Deliveries</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-clock"></i> Availability</div>
            <div class="card-body">
                <p>Set your available hours and working location.</p>
                <a href="{{ route('driver.availability') }}" class="btn btn-primary">Set Availability</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-calendar"></i> Delivery Calendar</div>
            <div class="card-body">
                <p>View your delivery schedule on the calendar.</p>
                <a href="{{ route('driver.calendar') }}" class="btn btn-primary">View Calendar</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header"><i class="fas fa-dollar-sign"></i> Earnings</div>
            <div class="card-body">
                <p>Track your revenue and commission details.</p>
                <a href="{{ route('driver.earnings') }}" class="btn btn-primary">View Earnings</a>
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
            labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'],
            datasets: [{
                label: 'Deliveries',
                data: {!! json_encode($chartData ?? [0, 0, 0, 0, 0, 0, 0]) !!},
                backgroundColor: '#4a90e2'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
@endsection
