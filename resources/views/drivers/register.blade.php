<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Driver Registration</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #strengthMessage {
            font-weight: bold;
        }
        .progress {
            height: 10px;
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
            <h4 class="mb-0">Driver Registration</h4>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('driver.register') }}" id="registerForm">
                @csrf
                <input type="hidden" name="fcm_token" id="fcm_token">

                <div class="mb-3">
                    <label>Name</label>
                    <input type="text" name="name" class="form-control required-field">
                </div>

                <div class="mb-3">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control required-field">
                </div>

                <div class="mb-3">
                    <label>Password</label>
                    <input type="password" name="password" id="password" class="form-control required-field" onkeyup="checkStrength(this.value)">
                    <small id="strengthMessage" class="text-muted"></small>
                </div>

                <div class="mb-3">
                    <label>Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control required-field">
                </div>

                <div class="form-check mb-3">
                    <input type="checkbox" class="form-check-input" id="togglePassword">
                    <label class="form-check-label" for="togglePassword">Show Password</label>
                </div>

                <div class="mb-3">
                     <label>Phone Number</label>
                    <input type="text" name="phone" class="form-control required-field">
                </div>

                <div class="mb-3">
                    <label>Vehicle Type</label>
                    <input type="text" name="vehicle_type" class="form-control required-field">
                </div>

                <div class="mb-3">
                    <label>Plate Number</label>
                    <input type="text" name="plate_number" class="form-control required-field">
                </div>

                <div class="mb-3">
                    <label>Pricing Model</label>
                    <select name="pricing_model" class="form-select required-field">
                        <option value="">-- Select --</option>
                        <option value="fixed">Fixed</option>
                        <option value="per_km">Per Kilometer</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label>Price</label>
                    <input type="number" name="price" class="form-control required-field" step="0.01">
                </div>

                <div class="progress mb-3">
                    <div id="formProgress" class="progress-bar bg-info" style="width: 0%"></div>
                </div>

                <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                    <span id="submitText">Register</span>
                    <span id="submitLoading" class="spinner-border spinner-border-sm d-none"></span>
                </button>

                <div class="mt-3 text-center">
                    <a href="{{ route('driver.login') }}">Already have an account?</a>
                </div>
            </form>
        </div>
    </div>
</div>

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

    function checkStrength(password) {
        let msg = '';
        if (password.length < 6) {
            msg = 'Too short';
            document.getElementById('strengthMessage').style.color = 'red';
        } else if (password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/[0-9]/)) {
            msg = 'Strong';
            document.getElementById('strengthMessage').style.color = 'green';
        } else {
            msg = 'Medium';
            document.getElementById('strengthMessage').style.color = 'orange';
        }
        document.getElementById('strengthMessage').textContent = msg;
    }

    document.getElementById('togglePassword').addEventListener('change', function () {
        const password = document.getElementById('password');
        password.type = this.checked ? 'text' : 'password';
    });

    const form = document.getElementById('registerForm');
    const progressBar = document.getElementById('formProgress');
    const requiredFields = document.querySelectorAll('.required-field');
    requiredFields.forEach(input => {
        input.addEventListener('input', updateProgress);
    });

    function updateProgress() {
        const filled = Array.from(requiredFields).filter(input => input.value.trim() !== '').length;
        const percent = (filled / requiredFields.length) * 100;
        progressBar.style.width = percent + '%';
    }

    form.addEventListener('submit', function () {
        document.getElementById('submitBtn').disabled = true;
        document.getElementById('submitText').classList.add('d-none');
        document.getElementById('submitLoading').classList.remove('d-none');
    });
</script>
</body>
</html>
