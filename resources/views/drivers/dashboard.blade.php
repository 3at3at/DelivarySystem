<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #fff;
        }

        .navbar {
            background-color: #004080;
        }

        .navbar-brand,
        .btn-outline-light {
            color: #fff !important;
        }

        .alert-info {
            background-color: #e0f0ff;
            color: #004080;
            border-color: #b3daff;
        }

        .card-header {
            background-color: #004080;
            color: #fff;
        }

        .btn-custom {
            background-color: #004080;
            color: white;
        }

        .btn-custom:hover {
            background-color: #00264d;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg px-3">
    <a class="navbar-brand" href="#">Driver Dashboard</a>
    <form method="POST" action="{{ route('driver.logout') }}" class="ms-auto">
        @csrf
        <button type="submit" class="btn btn-outline-light">Logout</button>
    </form>
</nav>

<div class="container mt-4">
    <div class="alert alert-info">
        Welcome, <strong>{{ auth()->guard('driver')->user()->name }}</strong>! You are logged in as a <strong>Driver</strong>.
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Deliveries</div>
                <div class="card-body">
                    <p>View your current deliveries and update their status.</p>
                    <a href="{{ route('driver.deliveries') }}" class="btn btn-custom">Manage Deliveries</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Availability</div>
                <div class="card-body">
                    <p>Set your available hours and working location.</p>
                    <a href="{{ route('driver.availability') }}" class="btn btn-custom">Set Availability</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Delivery Calendar</div>
                <div class="card-body">
                    <p>View your delivery schedule on the calendar.</p>
                    <a href="{{ route('driver.calendar') }}" class="btn btn-custom">View Calendar</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header">Earnings</div>
                <div class="card-body">
                    <p>Track your revenue and commission details.</p>
                    <a href="{{ route('driver.earnings') }}" class="btn btn-custom">View Earnings</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
