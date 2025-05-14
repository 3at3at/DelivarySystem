@extends('layouts.app')

@section('content')
<div class="max-w-lg mx-auto bg-white p-8 shadow-md rounded">
    <h2 class="text-2xl font-bold mb-6">Client Registration</h2>

    @if($errors->any())
        <div class="mb-4 text-red-600">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form method="POST" action="{{ route('client.register') }}">
        @csrf

        <div class="mb-4">
            <label class="block">Name</label>
            <input type="text" name="name" value="{{ old('name') }}" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block">Phone</label>
            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block">Address</label>
            <input type="text" name="address" value="{{ old('address') }}" class="w-full border px-3 py-2 rounded">
        </div>
<div class="mb-3">
    <label>Preferred Currency</label>
    <select name="preferred_currency" class="form-control">
        <option value="USD">$ USD</option>
        <option value="EUR">€ EUR</option>
        <option value="LBP">ل.ل LBP</option>
    </select>
</div>

        <div class="mb-4">
            <label class="block">Password</label>
            <input type="password" name="password" required class="w-full border px-3 py-2 rounded">
        </div>

        <div class="mb-4">
            <label class="block">Confirm Password</label>
            <input type="password" name="password_confirmation" required class="w-full border px-3 py-2 rounded">
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded w-full hover:bg-blue-700">
            Register
        </button>
    </form>
</div>
@endsection
