@extends('layouts.driver')

@section('content')
<div class="container mt-4">
    <h4>Set Your Availability</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ url('/driver/availability') }}">
        @csrf

        <div class="mb-3">
            <label>Working Hours</label>
            <input type="text" name="working_hours" class="form-control" value="{{ old('working_hours', $driver->working_hours) }}">
        </div>

        <div class="mb-3">
            <label>Location</label>
            <input type="text" name="location" class="form-control" value="{{ old('location', $driver->location) }}">
        </div>

        <div class="mb-3">
            <label>Available?</label>
            <select name="is_available" class="form-select">
                <option value="1" {{ $driver->is_available ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ !$driver->is_available ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
