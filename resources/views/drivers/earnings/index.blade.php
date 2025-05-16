@extends('layouts.driver')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="fas fa-wallet"></i> Earnings Summary</h5>
    </div>
    <div class="card-body">
        <p><strong>Total Earnings:</strong> ${{ number_format($totalRevenue ?? 0, 2) }}</p>

        <hr>

        <h6>Recent Deliveries</h6>
       <ul class="list-group">
    @forelse($recentDeliveries as $delivery)
        <li class="list-group-item">
            {{ $delivery->pickup_location }} to {{ $delivery->dropoff_location }} –
            ${{ number_format($delivery->price ?? 0, 2) }} 
            on {{ $delivery->scheduled_at ? \Carbon\Carbon::parse($delivery->scheduled_at)->format('Y-m-d H:i') : 'N/A' }}


        </li>
    @empty
        <li class="list-group-item">No recent deliveries.</li>
    @endforelse
</ul>

    </div>
</div>
@endsection
