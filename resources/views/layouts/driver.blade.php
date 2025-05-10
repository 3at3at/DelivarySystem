<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Driver Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<nav class="navbar navbar-dark" style="background-color: #004080;">

    <a class="navbar-brand" href="#"> Driver Panel</a>
    <form method="POST" action="{{ route('driver.logout') }}" class="ms-auto">
        @csrf
        <button type="submit" class="btn btn-outline-light">Logout</button>
    </form>
</nav>

<div class="container mt-4">
    @yield('content')
</div>

</body>
</html>
