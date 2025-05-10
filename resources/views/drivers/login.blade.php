<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-success text-white">
            <h4 class="mb-0">Driver Login</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('driver.login') }}">
                @csrf
                <input type="hidden" id="fcm_token" name="fcm_token">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Login</button>
                <div class="mt-3 text-center">
                    <a href="{{ route('driver.register') }}">Create a new account</a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Firebase Token --}}
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/8.10.0/firebase-messaging.js"></script>
<script>
    const firebaseConfig = {
        apiKey: "AIzaSyA71MhbzHkDxD0r5cWV3YcLbcSFg3VqMec",
        authDomain: "deliveryappsystem869.firebaseapp.com",
        projectId: "deliveryappsystem869",
        storageBucket: "deliveryappsystem869.firebasestorage.app",
        messagingSenderId: "683930011833",
        appId: "1:683930011833:web:d0bdc0951a73fd0a023368",
        measurementId: "G-KZDTHFWQPF"
    };

    firebase.initializeApp(firebaseConfig);

    const messaging = firebase.messaging();

    messaging.requestPermission()
        .then(() => messaging.getToken())
        .then((token) => {
            document.getElementById('fcm_token').value = token;

            // Optional: send to backend after login
            fetch("{{ url('/driver/save-token') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ token: token })
            });
        })
        .catch((err) => console.warn("FCM Error:", err));
</script>
</body>
</html>
