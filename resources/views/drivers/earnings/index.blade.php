@extends('layouts.driver')

@section('content')
<div class="container mt-4">
    <h3>Earnings Dashboard</h3>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Total Revenue: <span class="text-success">${{ number_format($totalRevenue, 2) }}</span></h5>
            <h5>Commission (10%): <span class="text-danger">-${{ number_format($totalCommission, 2) }}</span></h5>
            <h5>Net Earnings: <span class="text-primary">${{ number_format($netEarnings, 2) }}</span></h5>
        </div>
    </div>

    <h5>Completed Deliveries</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pickup</th>
                <th>Dropoff</th>
                <th>Price</th>
                <th>Scheduled At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($completedDeliveries as $delivery)
                <tr>
                    <td>{{ $delivery->pickup_location }}</td>
                    <td>{{ $delivery->dropoff_location }}</td>
                    <td>${{ number_format($delivery->price, 2) }}</td>
                    <td>{{ $delivery->scheduled_at }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
