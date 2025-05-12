<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin - Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">📦 Delivery Orders</h2>

    <!-- 🔍 Filter Dropdown + Flash Message -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <form method="GET" action="{{ route('admin.orders') }}">
            <select name="status" class="form-select" onchange="this.form.submit()" style="width: 200px;">
                <option value="">All Statuses</option>
                @foreach (['pending', 'assigned', 'in_progress', 'completed', 'cancelled'] as $stat)
                    <option value="{{ $stat }}" {{ request('status') === $stat ? 'selected' : '' }}>
                        {{ ucfirst($stat) }}
                    </option>
                @endforeach
            </select>
        </form>

        @if(session('success'))
            <div class="alert alert-success mb-0">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger mb-0">{{ session('error') }}</div>
        @endif
    </div>

    <!-- 📦 Orders Table -->
    <table class="table table-bordered table-hover bg-white shadow-sm">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Client</th>
                <th>Driver</th>
                <th>Pickup</th>
                <th>Dropoff</th>
                <th>Status</th>
                <th>Price</th>
                <th>Created</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($orders as $order)
                <tr @if($order->driver_status === 'rejected') class="table-danger" @endif>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->client->name ?? 'N/A' }}</td>
                    <td>
    @if ($order->driver && $order->driver_status !== 'rejected')
        {{ $order->driver->name }}
    @elseif ($order->driver_status === 'rejected' && $order->status !== 'completed')
        <form action="{{ route('admin.orders.assign', $order->id) }}" method="POST" class="d-flex gap-2 align-items-center">
            @csrf
            <select name="driver_id" class="form-select form-select-sm driver-search" style="width: 200px;">
                <option value="">Search available driver...</option>
            </select>
            <button type="submit" class="btn btn-sm btn-warning">Reassign</button>
        </form>
    @else
        <span class="text-muted">No driver</span>
    @endif
</td>

                    <td>{{ $order->pickup_location }}</td>
                    <td>{{ $order->dropoff_location }}</td>
                    <td>
                        <span class="badge bg-{{ 
                            $order->status === 'completed' ? 'success' : 
                            ($order->status === 'cancelled' ? 'danger' : 
                            ($order->status === 'in_progress' ? 'info' : 
                            ($order->status === 'assigned' ? 'warning' : 'secondary')))
                        }}">
                            {{ ucfirst($order->status) }}
                        </span>

                        @if($order->driver_status === 'rejected')
                            <span class="badge bg-danger ms-1">Rejected by Driver</span>
                        @endif
                    </td>
                    <td>${{ $order->price ?? '-' }}</td>
                    <td>{{ $order->created_at->diffForHumans() }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- ✅ Scripts for Select2 -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    $(document).ready(function () {
        $('.driver-search').select2({
            placeholder: 'Search driver...',
            ajax: {
                url: '{{ route("admin.drivers.search") }}',
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return { q: params.term };
                },
                processResults: function (data) {
                    return {
                        results: data.map(driver => ({
                            id: driver.id,
                            text: driver.name
                        }))
                    };
                },
                cache: true
            }
        });
    });
</script>

</body>
</html>
