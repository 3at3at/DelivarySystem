

<div class="container">
    <h2>Profile Information</h2>
    <ul>
        <li><strong>Name:</strong> {{ $client->name }}</li>
        <li><strong>Email:</strong> {{ $client->email }}</li>
        <li><strong>Phone:</strong> {{ $client->phone ?? 'Not set' }}</li>
        <li><strong>Address:</strong> {{ $client->address ?? 'Not set' }}</li>
    </ul>
</div>

