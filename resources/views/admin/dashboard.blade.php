<!DOCTYPE html>
<html lang="km">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SSMS</title>
    <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 64 64'%3E%3Crect width='64' height='64' rx='14' fill='%23198754'/%3E%3Cpath fill='%23ffffff' d='M18 22h28l-3 24H21l-3-24zm8-4a6 6 0 0 1 12 0h-4a2 2 0 0 0-4 0h-4z'/%3E%3C/svg%3E">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark text-white sidebar collapse vh-100 p-3">
                <h4 class="text-center py-3 fw-bold border-bottom border-secondary">SSMS Admin</h4>
                <ul class="nav flex-column mt-3">
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.dashboard') }}"
                           class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'active bg-success rounded' : '' }}">
                            <i class="fas fa-home me-2"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.categories.index') }}"
                           class="nav-link text-white {{ request()->routeIs('admin.categories.*') ? 'active bg-success rounded' : '' }}">
                            <i class="fas fa-tags me-2"></i> Categories
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('admin.products.index') }}"
                           class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'active bg-success rounded' : '' }}">
                            <i class="fas fa-boxes me-2"></i> Products (Stock)
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#"
                           class="nav-link text-white"
                           onclick="alert('Staff Management កំពុងរៀបចំ'); return false;">
                            <i class="fas fa-users me-2"></i> Staff Management
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('pos.orders') }}"
                           class="nav-link text-white {{ request()->routeIs('pos.orders') ? 'active bg-success rounded' : '' }}">
                            <i class="fas fa-chart-bar me-2"></i> Sales Reports
                        </a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('pos.index') }}"
                           class="nav-link text-info">
                            <i class="fas fa-cash-register me-2"></i> POS Cashier
                        </a>
                    </li>
                    <li class="nav-item mt-4">
                        <a href="{{ route('login') }}" class="nav-link text-danger">
                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                        </a>
                    </li>
                </ul>
            </nav>

            <!-- Main Content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2 fw-bold">Admin Dashboard Overview</h1>
                    <span class="text-muted">Welcome back, Admin</span>
                </div>

                <!-- Cards Statistics -->
                <div class="row text-white g-3 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary p-3 shadow-sm border-0">
                            <h5>Total Products</h5>
                            <h3>120 Items</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success p-3 shadow-sm border-0">
                            <h5>Today's Sales</h5>
                            <h3>$450.00</h3>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-warning text-dark p-3 shadow-sm border-0">
                            <h5>Total Staff</h5>
                            <h3>5 Members</h3>
                        </div>
                    </div>
                </div>

                <!-- Recent Activities / Table Placeholder -->
                <div class="card shadow-sm border-0 p-3">
                    <h5 class="fw-bold mb-3">Recent Transactions</h5>
                    <p class="text-muted">តារាងបង្ហាញប្រវត្តិនៃការទិញ-លក់ទំនិញនឹងត្រូវដាក់បញ្ចូលនៅទីនេះ...</p>
                </div>
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>