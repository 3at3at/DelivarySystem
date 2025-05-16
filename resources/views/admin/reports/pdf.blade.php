<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
    <h2>Delivery System Report</h2>
    <p><strong>Total Earnings:</strong> ${{ number_format($totalEarnings, 2) }}</p>

    <h3>Top 5 Drivers</h3>
    <table>
        <thead><tr><th>Name</th><th>Deliveries</th></tr></thead>
        <tbody>
            @foreach($topDrivers as $driver)
                <tr><td>{{ $driver->name }}</td><td>{{ $driver->deliveries_count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h3>Top 5 Clients</h3>
    <table>
        <thead><tr><th>Name</th><th>Orders</th></tr></thead>
        <tbody>
            @foreach($topClients as $client)
                <tr><td>{{ $client->name }}</td><td>{{ $client->deliveries_count }}</td></tr>
            @endforeach
        </tbody>
    </table>

    <h3>Recent Deliveries</h3>
    <table>
        <thead><tr><th>ID</th><th>Client</th><th>Driver</th><th>Price</th><th>Status</th></tr></thead>
        <tbody>
            @foreach($deliveries as $delivery)
                <tr>
                    <td>{{ $delivery->id }}</td>
                    <td>{{ $delivery->client->name ?? 'N/A' }}</td>
                    <td>{{ $delivery->driver->name ?? 'Unassigned' }}</td>
                    <td>${{ number_format($delivery->price, 2) }}</td>
                    <td>{{ ucfirst($delivery->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
