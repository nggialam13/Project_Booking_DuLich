<!DOCTYPE html>
<html>

<head>
    <title>Booking Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">Booking</a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
                ☰
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="nav-link" href="/">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/tours">Tour</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/bookings">Booking</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/login">Đăng nhập</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    @yield('content')



    <footer class="bg-dark text-white text-center p-3 mt-5">
        <p>© 2026 Booking Du Lịch | Nhóm H</p>
    </footer>

</body>

</html>