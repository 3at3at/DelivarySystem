<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Driver;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class DriverAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('drivers.register');
    }

    public function register(Request $request)
    {
        $request->validate([
    'name' => 'required',
    'email' => 'required|email|unique:drivers',
    'phone' => 'required|string|max:15',
    'password' => 'required|confirmed',
    'vehicle_type' => 'required',
    'plate_number' => 'required',
    'pricing_model' => 'required|in:fixed,per_km',
     'location' => 'required|string|max:100',
    'price' => 'required|numeric',
    'fcm_token' => 'nullable|string'
]);


        $driver = Driver::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'vehicle_type' => $request->vehicle_type,
            'plate_number' => $request->plate_number,
            'pricing_model' => $request->pricing_model,
             'location' => $request->location,
            'price' => $request->price,
            'scheduled_at' => $request->scheduled_at,
            'fcm_token' => $request->fcm_token,
        ]);

        return redirect()->route('driver.login')->with('success', 'Registration successful. Please log in.');

    }

    public function showLoginForm()
    {
        return view('drivers.login');
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    // Attempt login
    if (Auth::guard('driver')->attempt($credentials)) {
        $driver = Auth::guard('driver')->user();

        // ❌ Block based on status
        if ($driver->status === 'pending') {
            Auth::guard('driver')->logout();
            return back()->withErrors(['email' => 'Your account is pending approval. Please wait for admin.']);
        }

        if ($driver->status === 'suspended') {
            Auth::guard('driver')->logout();
            return back()->withErrors(['email' => 'Your account is suspended. Contact support.']);
        }

        if ($driver->status === 'blocked') {
            Auth::guard('driver')->logout();
            return back()->withErrors(['email' => 'Your account is blocked. Access denied.']);
        }

        // ✅ Approved
        return redirect()->route('driver.dashboard');
    }

    return back()->withErrors(['email' => 'Invalid credentials']);
}


    public function logout()
    {
        Auth::guard('driver')->logout();
        return redirect()->route('driver.login');
    }

    public function saveToken(Request $request)
{
    $request->validate([
        'token' => 'required|string'
    ]);

    $driver = Auth::guard('driver')->user();

    if ($driver) {
        $driver->update(['fcm_token' => $request->token]);
        return response()->json(['status' => 'Token saved']);
    }

    return response()->json(['status' => 'Unauthorized'], 401);
}

}
