<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Panel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5faff;
            animation: fadeIn 0.5s ease-in;
        }
        .sidebar {
            width: 220px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #4a90e2;
            color: #fff;
            padding-top: 60px;
        }
        .sidebar a {
            color: #fff;
            display: block;
            padding: 12px 20px;
            text-decoration: none;
            transition: 0.3s;
        }
        .sidebar a:hover {
            background-color: #2d6db8;
        }
        .content {
            margin-left: 240px;
            padding: 20px;
        }
        .form-label {
            font-weight: bold;
        }
        .form-select, .form-control {
            border-radius: 0.25rem;
        }
        .btn-custom {
            background-color: #4a90e2;
            color: white;
        }
        .btn-custom:hover {
            background-color: #2d6db8;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
<div class="sidebar">
    <h5 class="text-center">Driver Panel</h5>
    <a href="{{ route('driver.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
    <a href="{{ route('driver.profile') }}"><i class="fas fa-user"></i> My Profile</a>
    <a href="{{ route('driver.deliveries') }}"><i class="fas fa-truck"></i> Deliveries</a>
    <a href="{{ route('driver.availability') }}"><i class="fas fa-clock"></i> Availability</a>
    <a href="{{ route('driver.calendar') }}"><i class="fas fa-calendar"></i> Calendar</a>
    <a href="{{ route('driver.earnings') }}"><i class="fas fa-dollar-sign"></i> Earnings</a>
    <form method="POST" action="{{ route('driver.logout') }}" class="mt-3 text-center">
        @csrf
        <button type="submit" class="btn btn-outline-light btn-sm">Logout</button>
    </form>
</div>

<div class="content">
    @yield('content')
</div>
</body>
</html>
