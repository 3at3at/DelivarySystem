@extends('layouts.driver')

@section('content')
<div class="card shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0">Set Your Availability</h5>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ url('/driver/availability') }}">
            @csrf

            @php
                $workingHours = old('working_hours', auth()->guard('driver')->user()->working_hours);
                $defaults = [
                    'Monday' => '', 'Tuesday' => '', 'Wednesday' => '', 'Thursday' => '',
                    'Friday' => '', 'Saturday' => '', 'Sunday' => ''
                ];
                $lines = explode("\n", $workingHours);
                foreach ($lines as $line) {
                    [$day, $range] = array_pad(explode(':', $line, 2), 2, '');
                    $day = trim($day);
                    if (array_key_exists($day, $defaults)) {
                        $defaults[$day] = trim($range);
                    }
                }
            @endphp

            @foreach($defaults as $day => $value)
                <div class="mb-3">
                    <label class="form-label">{{ $day }}</label>
                    <input type="text" name="working_schedule[{{ $day }}]" class="form-control" placeholder="e.g. 09:00 - 17:00" value="{{ $value }}">
                </div>
            @endforeach

            <div class="mb-3">
                <label for="location" class="form-label">Location</label>
                <input type="text" name="location" class="form-control" value="{{ old('location', auth()->guard('driver')->user()->location) }}">
            </div>

            <div class="mb-3">
                <label for="is_available" class="form-label">Available?</label>
                <select name="is_available" class="form-select">
                    <option value="1" {{ auth()->guard('driver')->user()->is_available ? 'selected' : '' }}>Yes</option>
                    <option value="0" {{ !auth()->guard('driver')->user()->is_available ? 'selected' : '' }}>No</option>
                </select>
            </div>

            <button type="submit" class="btn btn-custom">Update</button>
        </form>
    </div>
</div>
@endsection
