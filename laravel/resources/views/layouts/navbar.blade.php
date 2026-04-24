<nav class="navbar navbar-expand-lg navbar-dark bg-main shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold fs-4" href="/tours">
            🌍 TravelPro
        </a>

        <div class="d-flex gap-2 align-items-center">

            @auth
                <a href="/tours" class="btn btn-light btn-sm">
                    <i class="fa fa-map"></i> Tours
                </a>

                <a href="/bookings" class="btn btn-outline-light btn-sm">
                    <i class="fa fa-ticket"></i> Booking
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="/admin/dashboard" class="btn btn-warning btn-sm">
                        <i class="fa fa-chart-bar"></i> Admin
                    </a>
                @endif

                <form action="/logout" method="POST">
                    @csrf
                    <button class="btn btn-danger btn-sm">
                        <i class="fa fa-sign-out-alt"></i>
                    </button>
                </form>
            @endauth

            @guest
                <a href="/login" class="btn btn-light btn-sm">Login</a>
                <a href="/register" class="btn btn-outline-light btn-sm">Register</a>
            @endguest

        </div>
    </div>
</nav>