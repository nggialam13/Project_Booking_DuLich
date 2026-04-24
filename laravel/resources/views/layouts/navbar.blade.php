<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="/">🌍 Travel</a>

            <div>
                <a href="/tours" class="btn btn-light me-2">Tours</a>
                <a href="/bookings" class="btn btn-outline-light">Booking</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-light">Đăng xuất</button>
                </form>
            </div>
        </div>
    </nav>
</nav>