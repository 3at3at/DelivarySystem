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

    <div class="card">
        <div class="card-body">
            <h5>Service Demand (Last 30 Days)</h5>
            <ul>
                @foreach ($dailyTrends as $trend)
                    <li>{{ $trend->date }}: {{ $trend->count }} deliveries</li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
</body>
</html>
