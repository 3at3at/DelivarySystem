<h2>You've been assigned a new delivery!</h2>
<p><strong>Pickup:</strong> {{ $order->pickup_location }}</p>
<p><strong>Dropoff:</strong> {{ $order->dropoff_location }}</p>
<p><strong>Client:</strong> {{ $order->client->name ?? 'N/A' }}</p>
<p>Log in to your dashboard to view details.</p>
