<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
{
    public function dashboard()
    {
        $client = Auth::user(); // User is the Client
        return view('client.dashboard', compact('client'));
    }

    public function profile()
    {
        $client = Auth::user();
        return view('client.profile', compact('client'));
    }
}
