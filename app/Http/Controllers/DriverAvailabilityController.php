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
            'working_hours' => 'nullable|string',
            'location' => 'nullable|string',
            'is_available' => 'required|boolean'
        ]);

        $driver->update($request->only('working_hours', 'location', 'is_available'));

        return back()->with('success', 'Availability updated.');
    }
}

