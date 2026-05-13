<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Admin - Travel Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Icon -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Admin Forms CSS -->
    <link href="{{ asset('css/admin-forms.css') }}" rel="stylesheet">

    <style>
        * {
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-color: #f5f5f5;
            overflow-x: hidden;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, #1a1a2e 0%, #16213e 100%);
            box-shadow: 4px 0 15px rgba(0, 0, 0, 0.3);
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar-header {
            padding: 20px;
            border-bottom: 2px solid #0f3460;
            margin-bottom: 20px;
            text-align: center;
        }

        .sidebar-header h3 {
            color: #fff;
            margin: 0;
            font-weight: 700;
            font-size: 20px;
            letter-spacing: 1px;
        }

        .sidebar-header small {
            color: #0dcaf0;
            font-size: 12px;
        }

        .sidebar-nav {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-nav li {
            margin: 5px 0;
        }

        .sidebar-nav a {
            color: #b8c1cc;
            text-decoration: none;
            padding: 12px 20px;
            display: flex;
            align-items: center;
            transition: all 0.3s ease;
            border-left: 4px solid transparent;
            font-size: 14px;
        }

        .sidebar-nav a:hover,
        .sidebar-nav a.active {
            color: #0dcaf0;
            background-color: rgba(13, 202, 240, 0.1);
            border-left-color: #0dcaf0;
            padding-left: 24px;
        }

        .sidebar-nav i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        /* ===== MAIN CONTENT ===== */
        .main-wrapper {
            margin-left: 260px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ===== HEADER ===== */
        .admin-header {
            background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
            color: white;
            padding: 20px 30px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .admin-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }

        .admin-header-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, #0dcaf0, #0d6efd);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
        }

        /* ===== CONTENT ===== */
        .admin-content {
            flex: 1;
            padding: 30px;
            padding-top: 20px;
        }

        /* ===== CARD ===== */
        .card-admin {
            border: none;
            border-radius: 12px;
            background: white;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }

        .card-admin:hover {
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
            transform: translateY(-3px);
        }

        .card-admin-header {
            border-bottom: 1px solid #e9ecef;
            padding: 20px;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            border-radius: 12px 12px 0 0;
        }

        .card-admin-header h5 {
            margin: 0;
            color: #1a1a2e;
            font-weight: 600;
        }

        .card-admin-body {
            padding: 20px;
        }

        /* ===== BUTTON ===== */
        .btn-admin {
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-admin-primary {
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
            color: white;
        }

        .btn-admin-primary:hover {
            background: linear-gradient(135deg, #0dcaf0, #0d6efd);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(13, 110, 253, 0.4);
            color: white;
        }

        .btn-admin-danger {
            background: #dc3545;
            color: white;
        }

        .btn-admin-danger:hover {
            background: #c82333;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
            color: white;
        }

        .btn-admin-success {
            background: #28a745;
            color: white;
        }

        .btn-admin-success:hover {
            background: #218838;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(40, 167, 69, 0.4);
            color: white;
        }

        /* ===== TABLE ===== */
        .table-admin {
            background: white;
            border-radius: 12px;
            overflow: hidden;
        }

        .table-admin thead {
            background: linear-gradient(135deg, #0f3460, #16213e);
            color: white;
        }

        .table-admin thead th {
            border: none;
            font-weight: 600;
            padding: 15px;
            text-align: left;
        }

        .table-admin tbody tr {
            border-bottom: 1px solid #e9ecef;
            transition: all 0.3s ease;
        }

        .table-admin tbody tr:hover {
            background-color: #f8f9fa;
        }

        .table-admin tbody td {
            padding: 15px;
            vertical-align: middle;
        }

        /* ===== ALERT ===== */
        .alert-admin {
            border-radius: 12px;
            border: none;
            margin-bottom: 20px;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .alert-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        /* ===== FOOTER ===== */
        .admin-footer {
            background: #1a1a2e;
            color: #b8c1cc;
            text-align: center;
            padding: 20px;
            border-top: 1px solid #0f3460;
            margin-top: auto;
        }

        .admin-footer p {
            margin: 0;
            font-size: 13px;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 250px;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-wrapper {
                margin-left: 0;
            }

            .admin-header h1 {
                font-size: 18px;
            }

            .admin-content {
                padding: 15px;
            }
        }

        /* ===== ANIMATION ===== */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ===== BADGE ===== */
        .badge-status {
            border-radius: 20px;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge-status-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-status-danger {
            background-color: #f8d7da;
            color: #721c24;
        }

        .badge-status-warning {
            background-color: #fff3cd;
            color: #856404;
        }
    </style>
</head>

<body>
    <!-- SIDEBAR -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h3>
                <i class="fas fa-tachometer-alt"></i> Admin
            </h3>
            <small>Dashboard</small>
        </div>

        <ul class="sidebar-nav">
            //đường dẫn quản lý người dùng
            <li>
                <a href="{{ route('admin.users') }}" class="@if(request()->routeIs('admin.users*')) active @endif">
                    <i class="fas fa-users"></i> Quản lý người dùng
                </a>
            </li>
            <li><a href="{{ route('tours.index') }}" class="@if(request()->routeIs('tours.*')) active @endif">
                    <i class="fas fa-map-location-dot"></i> Tours
                </a>
            </li>
            @if(Route::has('admin.bookings.index'))
                <li><a href="{{ route('admin.bookings.index') }}"
                        class="@if(request()->routeIs('admin.bookings.*')) active @endif">
                        <i class="fas fa-calendar-check"></i> Bookings
                    </a>
                </li>
            @endif
            @if(Route::has('payment.index'))
                <li><a href="{{ route('payment.index') }}" class="@if(request()->routeIs('payment.*')) active @endif">
                        <i class="fas fa-credit-card"></i> Payments
                    </a>
                </li>
            @endif

            @if(Route::has('admin.report'))
                <li>
                    <a href="{{ route('admin.report') }}" class="@if(request()->routeIs('admin.report')) active @endif">
                        <i class="fas fa-chart-bar"></i> Report
                    </a>
                </li>
            @endif
            <li>
                <hr style="border-color: #0f3460; margin: 10px 0;">
            </li>
            <li><a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
            
        </ul>

    </div>

    <!-- MAIN WRAPPER -->
    <div class="main-wrapper">
        <!-- HEADER -->
        <div class="admin-header">
            <h1>
                <i class="fas fa-bars" style="cursor: pointer; display: none;" onclick="toggleSidebar()"></i>
                Admin Panel
            </h1>
            <div class="admin-header-right">
                <div class="user-info">
                    <div class="user-avatar">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <strong style="color: white;">{{ Auth::user()->name ?? 'Admin' }}</strong><br>
                        <small style="color: #0dcaf0;">Administrator</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- CONTENT -->
        <div class="admin-content fade-in">
            @include('layouts.alert')

            @yield('content')
        </div>

        <!-- FOOTER -->
        <div class="admin-footer">
            <p>&copy; 2026 Travel Booking Admin. All rights reserved.</p>
        </div>
    </div>

    <!-- HIDDEN LOGOUT FORM -->
    <form id="logout-form" action="{{ route('logout') ?? '#' }}" method="POST" style="display: none;">
        @csrf
    </form>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('show');
        }

        // Auto calculate duration when start_date or end_date changes
        document.addEventListener('DOMContentLoaded', function () {
            const startDateInput = document.getElementById('start_date');
            const endDateInput = document.getElementById('end_date');
            const durationInput = document.getElementById('duration');

            function calculateDuration() {
                if (!startDateInput.value || !endDateInput.value) return;

                const startDate = new Date(startDateInput.value);
                const endDate = new Date(endDateInput.value);

                // Check date hợp lệ
                if (isNaN(startDate) || isNaN(endDate)) return;

                if (endDate < startDate) {
                    durationInput.value = '';
                    return;
                }

                const diffTime = endDate - startDate;
                const diffDays = diffTime / (1000 * 60 * 60 * 24) + 1;

                durationInput.value = diffDays;
            }

            startDateInput?.addEventListener('change', calculateDuration);
            endDateInput?.addEventListener('change', calculateDuration);
        });
    </script>
</body>

</html>