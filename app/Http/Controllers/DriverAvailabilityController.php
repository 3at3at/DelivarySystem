<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DriverAvailabilityController extends Controller
{
    public function edit()
    {
        $driver = Auth::guard('driver')->user();
        return view('drivers.availability.edit', compact('driver'));
    }

    public function update(Request $request)
    {
        $driver = Auth::guard('driver')->user();

        $request->validate([
            'working_schedule' => 'required|array',
            'location' => 'nullable|string',
            'is_available' => 'required|boolean',
        ]);

        // Convert schedule to a formatted string (one line per day)
        $working_hours = collect($request->working_schedule)
            ->map(fn($time, $day) => "$day: $time")
            ->implode("\n");

        $driver->update([
            'working_hours' => $working_hours,
            'location' => $request->location,
            'is_available' => $request->is_available,
        ]);

        return back()->with('success', 'Availability updated successfully.');
    }
}
