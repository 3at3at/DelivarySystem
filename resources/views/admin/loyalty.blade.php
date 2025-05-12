<!DOCTYPE html>
<html>
<head>
    <title>Loyalty Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <h3 class="mb-4">🎁 Loyalty Program Settings</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.loyalty.update') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Points Per Kilometer</label>
            <input type="number" step="0.1" class="form-control" name="points_per_km" value="{{ $setting->points_per_km ?? 1 }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Bonus Threshold (points)</label>
            <input type="number" class="form-control" name="bonus_threshold" value="{{ $setting->bonus_threshold ?? 100 }}" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Bonus Reward (%)</label>
            <input type="number" step="0.1" class="form-control" name="bonus_reward" value="{{ $setting->bonus_reward ?? 5 }}" required>
        </div>

        <button type="submit" class="btn btn-primary">💾 Save Settings</button>
    </form>
</div>
</body>
</html>
