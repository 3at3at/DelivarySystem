@extends('layouts.driver') {{-- Optional: use same layout for dashboard --}}

@section('content')
<div class="container mt-4">
    <h4>Your Assigned Deliveries</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Pickup</th>
                <th>Dropoff</th>
                <th>Details</th>
                <th>Status</th>
                <th>Scheduled</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($deliveries as $delivery)
            <tr>
                <td>{{ $delivery->pickup_location }}</td>
                <td>{{ $delivery->dropoff_location }}</td>
                <td>{{ $delivery->package_details }}</td>
                <td>{{ $delivery->status }}</td>
                <td>{{ $delivery->scheduled_at }}</td>
                <td>
                    <form method="POST" action="{{ route('driver.delivery.update', $delivery->id) }}">
                        @csrf
                        <select name="status" class="form-select form-select-sm d-inline w-auto">
                            <option {{ $delivery->status == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                            <option {{ $delivery->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option {{ $delivery->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                            <option {{ $delivery->status == 'Cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                        <button type="submit" class="btn btn-sm btn-primary">Update</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
