<!DOCTYPE html>
<html>
<head>
    <title>Admin Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4">📊 Reports Dashboard</h3>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Total Revenue</h5>
            <p><strong>${{ number_format($totalRevenue, 2) }}</strong></p>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Driver Performance</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Driver</th>
                        <th>Completed Deliveries</th>
                        <th>Total Earnings</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($driverStats as $driver)
                        <tr>
                            <td>{{ $driver->name }}</td>
                            <td>{{ $driver->deliveries_count }}</td>
                            <td>${{ number_format($driver->total_earnings ?? 0, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5>Client Spending</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Client</th>
                        <th>Total Spending</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientStats as $client)
                        <tr>
                            <td>{{ $client->name }}</td>
                            <td>${{ number_format($client->deliveries_sum_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

<div class="card mb-4">
    <div class="card-body">
        <h5>📦 All Deliveries (Service Demand)</h5>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Order Number</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Client</th>
                    <th>Driver</th>
                    <th>Pickup</th>
                    <th>Dropoff</th>
                </tr>
            </thead>
            <tbody>
                @php $count = 1; @endphp
                @foreach (\App\Models\Delivery::with('client', 'driver')->latest()->get() as $delivery)
                    <tr>
                        <td>{{ $count++ }}</td>
                        <td>{{ \Carbon\Carbon::parse($delivery->scheduled_at)->format('Y-m-d H:i') }}</td>
                        <td><span class="badge bg-secondary">{{ $delivery->status }}</span></td>
                        <td>{{ $delivery->client->name ?? 'N/A' }}</td>
                        <td>{{ $delivery->driver->name ?? 'Unassigned' }}</td>
                        <td>{{ $delivery->pickup_location }}</td>
                        <td>{{ $delivery->dropoff_location }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>



  <div class="d-flex gap-2 mb-3">
    <a href="{{ route('admin.reports.pdf') }}" class="btn btn-outline-primary w-100" style="max-width: 200px;">
        Download PDF
    </a>

    <a href="{{ route('admin.reports.download.excel') }}" class="btn btn-outline-success w-100" style="max-width: 200px;">
        Download Excel
    </a>
</div>

</div>
</body>
</html>
