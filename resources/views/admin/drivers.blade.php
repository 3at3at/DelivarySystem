<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Manage Drivers</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <h2 class="mb-4 text-center">🚗 Driver Management Panel</h2>

        @if(session('success'))
            <div class="alert alert-success text-center">
                {{ session('success') }}
            </div>
        @endif

        <table class="table table-bordered table-hover bg-white shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Vehicle</th>
                    <th>Pricing</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($drivers as $driver)
                    <tr>
                        <td>{{ $driver->name }}</td>
                        <td>{{ $driver->email }}</td>
                        <td>
                            <span class="badge bg-{{ 
                                $driver->status === 'approved' ? 'success' : 
                                ($driver->status === 'suspended' ? 'warning' : 
                                ($driver->status === 'blocked' ? 'danger' : 'secondary')) 
                            }}">
                                {{ ucfirst($driver->status) }}
                            </span>
                        </td>
                        <td>{{ $driver->vehicle_type }} - {{ $driver->plate_number }}</td>
                        <td>{{ $driver->pricing_model }} ({{ $driver->price }}$)</td>
                        <td>{{ $driver->working_hours ?? 'Not set' }}</td>
                        <td>
                            <div class="d-flex gap-2">
                                @if ($driver->status !== 'approved')
                                    <form action="{{ route('admin.drivers.approve', $driver->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-success" type="submit">Approve</button>
                                    </form>
                                @endif
                                @if ($driver->status !== 'suspended')
                                    <form action="{{ route('admin.drivers.suspend', $driver->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-warning" type="submit">Suspend</button>
                                    </form>
                                @endif
                                @if ($driver->status !== 'blocked')
                                    <form action="{{ route('admin.drivers.block', $driver->id) }}" method="POST">
                                        @csrf
                                        <button class="btn btn-sm btn-danger" type="submit">Block</button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
