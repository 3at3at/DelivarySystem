@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto mt-10 p-8 border rounded shadow">
    <h2 class="text-2xl font-bold mb-6">Client Login</h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">{{ $errors->first() }}</div>
    @endif

    <form method="POST" action="{{ route('client.login') }}">
        @csrf

        <input type="email" name="email" placeholder="Email" class="input w-full mb-4" required>
        <input type="password" name="password" placeholder="Password" class="input w-full mb-4" required>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
            Login
        </button>
    </form>

    <p class="mt-4 text-center text-sm">
        Don't have an account?
        <a href="{{ route('client.register') }}" class="text-blue-600 underline">Register here</a>
    </p>
</div>
@endsection
