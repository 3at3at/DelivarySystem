@extends('layouts.driver')

@section('content')
<div class="container mt-5">
    <div class="alert alert-success text-center shadow">
        <h3>🎉 Loyalty Bonus Unlocked!</h3>
        <p><strong>{{ $clientName }}</strong> has earned a loyalty reward.</p>
        <p>🚚 They will receive a <strong>{{ $reward }}%</strong> discount on their next delivery.</p>
    </div>

    <div class="text-center mt-4">
        <a href="{{ route('driver.deliveries') }}" class="btn btn-primary">Back to Deliveries</a>
    </div>
</div>
@endsection
