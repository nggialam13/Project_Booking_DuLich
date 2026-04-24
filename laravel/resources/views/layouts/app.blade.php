<!DOCTYPE html>
<html>

<head>
    <title>Booking Tour</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<style>
/* HERO */
.hero-section {
    height: 80vh;
    background: url('https://images.unsplash.com/photo-1507525428034-b723cf961d3e') center/cover no-repeat;
    position: relative;
}
.hero-section::before {
    content: "";
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,0.5);
}
.hero-section > div {
    position: relative;
    z-index: 1;
}

/* CATEGORY CARD */
.category-card {
    transition: 0.3s;
    border-radius: 15px;
}
.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}

/* TOUR CARD */
.tour-card {
    transition: 0.3s;
}
.tour-card:hover {
    transform: scale(1.03);
    box-shadow: 0 15px 30px rgba(0,0,0,0.2);
}
</style>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">🌍 Travel</a>

            <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#menu">
                ☰
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">
                        <a class="btn btn-light me-2" href="/home">Trang chủ</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="/tours">Tour</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="/bookings">Booking</a>
                    </li>

                    <li class="nav-item">
                        <a class="btn btn-outline-light me-2" href="/login">Đăng nhập</a>
                    </li>

                </ul>
            </div>
        </div>
    </nav>

    @yield('content')



<footer class="bg-dark text-white text-center p-4 mt-5">
    <p class="mb-0">
        © 2026 Travel Booking System | Laravel Project
    </p>
</footer>

</body>

</html>