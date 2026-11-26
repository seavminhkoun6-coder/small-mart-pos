<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSMS Login - Small Mart Management System</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23198754'/%3E%3Cpath fill='%23ffffff' d='M18 22h28l-3 24H21l-3-24zm8-4a6 6 0 0 1 12 0h-4a2 2 0 0 0-4 0h-4z'/%3E%3C/svg%3E">
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    <div class="card shadow-sm p-4" style="width: 400px; border-radius: 10px;">
        <div class="text-center mb-4">
            <h4 class="fw-bold mt-2">Small Mart Management</h4>
            <p class="text-muted small">Sign in to your account (SSMS)</p>
        </div>

        <!-- បង្ហាញ Error ពេល Login ខុស -->
        @if($errors->any())
            <div class="alert alert-danger py-2 small">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ url('/login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label small fw-bold">Email or Username</label>
                <input type="text" name="email" class="form-control" value="{{ old('email') }}" required placeholder="Enter email or username">
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Password">
            </div>

            <div class="mb-3 form-check">
                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                <label class="form-check-label small" for="remember">Remember me</label>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2 fw-bold">Login</button>
        </form>

        <div class="text-center mt-3">
            <small class="text-muted">Role Access: Admin | Cashier</small>
        </div>
    </div>

</body>
</html>