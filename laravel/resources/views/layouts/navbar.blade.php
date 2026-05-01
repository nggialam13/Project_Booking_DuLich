<nav class="navbar navbar-expand-lg navbar-dark bg-main shadow-sm">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand brand-pro" href="/">
            🌍 TravelPro
        </a>

        <div class="collapse navbar-collapse">

            <!-- MENU -->
            <ul class="navbar-nav me-auto menu-pro">

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('tours*') ? 'active' : '' }}" href="/tours">
                        Tours
                    </a>
                </li>

                @auth
                    @if(auth()->user()->role === 'user')
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('bookings*') ? 'active' : '' }}" href="/bookings">
                            Booking
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('payments*') ? 'active' : '' }}" href="/payments">
                            Payment
                        </a>
                    </li>
                    @endif
                @endauth

            </ul>

            <!-- USER -->
            <div class="d-flex align-items-center gap-3 user-box">

                @auth
                    <span class="user-name">
                        👤 {{ auth()->user()->name }}
                    </span>

                    <form method="POST" action="/logout">
                        @csrf
                        <button class="btn btn-logout">
                            Logout
                        </button>
                    </form>
                @endauth

                @guest
                    <a href="/login" class="btn btn-light btn-sm">Login</a>
                    <a href="/register" class="btn btn-outline-light btn-sm">Register</a>
                @endguest

            </div>

        </div>
    </div>
</nav>