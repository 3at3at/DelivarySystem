<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>403 - Forbidden</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center py-5">
    <div class="container">
        <h1 class="display-4 text-danger">403 Forbidden</h1>
        <p class="lead">You are not authorized to access this page.</p>
        <a href="{{ route('driver.login') }}" class="btn btn-primary mt-3">Login as Driver</a>
    </div>
</body>
</html>
