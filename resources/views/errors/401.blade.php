<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>401</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light text-center py-5">
    <div class="container">
        <h1 class="display-4 text-danger">Error 401</h1>
        <p class="lead">You are not authorized to access this page.</p>
        
        <!-- Check if user is an admin or driver and display the appropriate login button -->
        @if( Route::is('admin.*'))
            <a href="{{ route('admin.login') }}" class="btn btn-primary mt-3">Please Login (Admin)</a>
        @elseif( Route::is('driver.*'))
            <a href="{{ route('driver.login') }}" class="btn btn-primary mt-3">Please Login (Driver)</a>
        @endif
    </div>
</body>
</html>
