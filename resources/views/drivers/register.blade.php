<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-lg">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Driver Registration</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('driver.register') }}">
                @csrf
                <input type="hidden" name="fcm_token" id="fcm_token">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control" required value="{{ old('name') }}">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Vehicle Type</label>
                    <input type="text" name="vehicle_type" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Plate Number</label>
                    <input type="text" name="plate_number" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label>Pricing Model</label>
                    <select name="pricing_model" class="form-select" required>
                        <option value="fixed">Fixed</option>
                        <option value="per_km">Per Kilometer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control" required step="0.01">
                </div>

                <div class="mb-3">
                    <label>Preferred Delivery Date/Time</label>
                    <input type="datetime-local" name="scheduled_at" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary w-100">Register</button>
                <div class="mt-3 text-center">
                    <a href="{{ route('driver.login') }}">Already have an account?</a>
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
        })
        .catch((err) => console.warn("FCM Error:", err));
</script>
</body>
</html>
