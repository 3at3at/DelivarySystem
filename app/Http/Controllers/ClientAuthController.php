<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('client.auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
             'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|min:6|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'preferred_currency' => 'nullable|string|max:10'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'preferred_currency' => $request->preferred_currency,
            'password' => Hash::make($request->password)
        ]);

        Auth::login($user);

        return redirect()->route('client.dashboard');
    }

    public function showLoginForm()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            return redirect()->route('client.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid login credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('client.login');
    }
}
