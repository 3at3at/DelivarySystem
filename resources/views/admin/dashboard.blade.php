<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .dashboard-btn {
            transition: all 0.3s ease;
        }

        .dashboard-btn:hover {
            transform: scale(1.1);
            background-color: #007bff;
            color: white;
        }

        .card-custom {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .card-custom-header {
            background-color: #007bff;
            color: white;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .card-custom-body {
            background-color: #f1f1f1;
            border-radius: 0 0 10px 10px;
        }

        .logout-btn {
            margin-bottom: 20px;
            font-size: 18px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            transform: scale(1.1);
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row">
        <div class="col-md-12 text-center">
            <h1 class="mb-4">Welcome to Admin Dashboard</h1>

            <!-- Logout Button -->
            <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn logout-btn">Logout</button>
            </form>

            <!-- Dashboard Buttons -->
            <div class="row mt-4">
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.deliveries') }}" class="btn btn-primary dashboard-btn btn-block py-3">Orders</a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.drivers') }}" class="btn btn-info dashboard-btn btn-block py-3">Drivers</a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.loyalty') }}" class="btn btn-success dashboard-btn btn-block py-3">Loyalty Settings</a>
                </div>
                <div class="col-md-3 mb-3">
                    <a href="{{ route('admin.reports') }}" class="btn btn-warning dashboard-btn btn-block py-3">Reports</a>
                </div>
            </div>

            <!-- Dashboard Stats -->
            <div class="row mt-5">
                <div class="col-md-6 mb-3">
                    <div class="card card-custom">
                        <div class="card-custom-header">
                            <h4>Available Drivers</h4>
                        </div>
                        <div class="card-custom-body">
                            <p>Total available drivers: {{ $activeDrivers }}</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div class="card card-custom">
                        <div class="card-custom-header">
                            <h4>Pending Deliveries</h4>
                        </div>
                        <div class="card-custom-body">
                            <p>Total pending deliveries: {{ $pendingDeliveries }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
