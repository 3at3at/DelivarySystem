<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login | SpeedGo</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Optional Custom Styles -->
    <style>
        body {
            background: #f1f5f9;
        }
        .login-card {
            background: white;
            border-radius: 12px;
            padding: 2rem;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
        }
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2563eb;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-light bg-white shadow-sm py-3">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🚚 SpeedGo Delivery</span>
        </div>
    </nav>

    <!-- Login Form Section -->
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="login-card w-100" style="max-width: 400px;">
            <h3 class="text-center text-primary mb-4">Admin Login</h3>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email address</label>
                    <input id="email" type="email" name="email" class="form-control" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>

                @if($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>

</body>
</html>
