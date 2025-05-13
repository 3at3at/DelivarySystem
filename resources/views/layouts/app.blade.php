<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Client Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>



<body class="bg-gray-100 text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-blue-600">
            🚚 SpeedGo Delivery
        </div>
        @auth
        <form method="POST" action="{{ route('client.logout') }}">
            @csrf
            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Logout
            </button>
        </form>
        @endauth
    </nav>

    <div class="container mx-auto mt-8 p-6 bg-white rounded shadow">
        @yield('content')
    </div>
@stack('scripts')
</body>
</html>
