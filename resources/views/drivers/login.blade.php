<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Login</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .spinner-border {
            vertical-align: middle;
        }
    </style>
</head>
<body class="bg-light">

   <nav class="bg-white shadow p-4 flex justify-between items-center">
        <div class="text-xl font-bold text-blue-600">
            🚚 SpeedGo Delivery
        </div>
    </nav>

<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Driver Login</h4>
        </div>
        <div class="card-body">
          <form method="POST" action="{{ route('driver.login.submit') }}" id="loginForm">

                @csrf
                <input type="hidden" id="fcm_token" name="fcm_token">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="togglePassword">
                    <label class="form-check-label" for="togglePassword">Show Password</label>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    <span id="submitText">Login</span>
                    <span id="submitLoading" class="spinner-border spinner-border-sm d-none"></span>
                </button>

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

    document.getElementById('togglePassword').addEventListener('change', function () {
        const password = document.getElementById('password');
        password.type = this.checked ? 'text' : 'password';
    });

    document.getElementById('loginForm').addEventListener('submit', function () {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitText').classList.add('d-none');
        document.getElementById('submitLoading').classList.remove('d-none');
    });
</script>
</body>
</html>
