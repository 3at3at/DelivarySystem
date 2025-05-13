<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;

class ClientDriverController extends Controller
{
    public function list()
    {
        $drivers = Driver::all();
        return view('client.drivers.list', compact('drivers'));
    }

  public function show($id)
{
    $driver = Driver::with(['deliveries', 'reviews.client'])->findOrFail($id);
    $averageRating = $driver->reviews()->avg('rating');

    return view('client.drivers.show', compact('driver', 'averageRating'));
}

}
