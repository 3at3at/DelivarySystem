@extends('layouts.driver')

@section('content')
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-truck"></i> Your Assigned Deliveries</h5>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Client</th>
                        <th>Pickup</th>
                        <th>Dropoff</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Scheduled</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($deliveries as $delivery)
                        <tr>
                            <td>{{ $delivery->client->name ?? 'N/A' }}</td>
                            <td>{{ $delivery->pickup_location }}</td>
                            <td>{{ $delivery->dropoff_location }}</td>
                            <td>
                                <strong>Type:</strong> {{ ucfirst($delivery->package_type ?? '-') }}<br>
                                <strong>Weight:</strong> {{ $delivery->package_weight ?? '-' }} kg<br>
                                <strong>Size:</strong> {{ $delivery->package_dimensions ?? '-' }}<br>
                                <strong>Payment:</strong> {{ strtoupper($delivery->payment_method ?? '-') }}
                            </td>
                            <td>
                                <span class="badge bg-info text-dark">{{ $delivery->status }}</span><br>
                                <small class="text-muted">Driver: {{ ucfirst($delivery->driver_status) }}</small>
                            </td>
                            <td>
                                {{ $delivery->scheduled_at ? \Carbon\Carbon::parse($delivery->scheduled_at)->format('Y-m-d H:i') : 'N/A' }}
                            </td>
                            <td>
                                @if($delivery->driver_status === 'pending')
                                    <form action="{{ route('driver.deliveries.accept', $delivery->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                    </form>

                                    <form action="{{ route('driver.deliveries.reject', $delivery->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                @elseif($delivery->driver_status === 'accepted')
                                    <form method="POST" action="{{ route('driver.delivery.update', $delivery->id) }}" class="d-flex gap-2 align-items-center flex-wrap">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm w-auto">
                                            <option value="Accepted" {{ $delivery->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                                            <option value="In Progress" {{ $delivery->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Delivered" {{ $delivery->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                                            <option value="Cancelled" {{ $delivery->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-outline-primary">Update</button>

                                        <a href="{{ route('driver.chat', ['deliveryId' => $delivery->id]) }}" class="btn btn-sm btn-primary">
    💬 Chat with Client
</a>

                                    </form>
                                @elseif($delivery->driver_status === 'rejected')
                                    <span class="text-danger">You rejected this</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No deliveries assigned.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
