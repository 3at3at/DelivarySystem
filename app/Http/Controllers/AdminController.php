<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;

class AdminController extends Controller
{
public function dashboard()
    {
        return view('admin.dashboard'); 
    }

    
public function drivers()
{
    $drivers = Driver::all();
    return view('admin.drivers', compact('drivers'));
}

public function approveDriver($id)
{
    $driver = Driver::findOrFail($id);
    $driver->status = 'approved';
    $driver->save();
    return redirect()->back()->with('success', 'Driver approved.');
}

public function suspendDriver($id)
{
    $driver = Driver::findOrFail($id);
    $driver->status = 'suspended';
    $driver->save();
    return redirect()->back()->with('success', 'Driver suspended.');
}

public function blockDriver($id)
{
    $driver = Driver::findOrFail($id);
    $driver->status = 'blocked';
    $driver->save();

    return redirect()->back()->with('success', 'Driver blocked successfully.');
}

}

