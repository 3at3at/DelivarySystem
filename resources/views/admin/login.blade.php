<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

   <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-blue-600">
            🚚 SpeedGo Delivery
        </div>
    </nav>

<div class="container py-5">
    <h2 class="text-center mb-4">Admin Login</h2>

    <form method="POST" action="{{ route('admin.login.submit') }}" class="mx-auto" style="max-width: 400px;">
        @csrf

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required autofocus>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        @if($errors->any())
            <div class="alert alert-danger">{{ $errors->first() }}</div>
        @endif

        <button class="btn btn-primary w-100">Login</button>
    </form>
</div>

</body>
</html>
