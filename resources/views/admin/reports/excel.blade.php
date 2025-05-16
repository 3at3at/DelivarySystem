<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Client</th>
            <th>Driver</th>
            <th>Price</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        @foreach($deliveries as $delivery)
            <tr>
                <td>{{ $delivery->id }}</td>
                <td>{{ $delivery->client->name ?? 'N/A' }}</td>
                <td>{{ $delivery->driver->name ?? 'Unassigned' }}</td>
                <td>{{ $delivery->price }}</td>
                <td>{{ ucfirst($delivery->status) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
