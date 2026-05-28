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
                            <a class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                                Booking
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('payment.*') ? 'active' : '' }}" href="{{ route('payment.index') }}">
                                Thanh toán
                            </a>
                        </li>
                    @endif
                @endauth

            </ul>

            <!-- USER -->
            <div class="d-flex align-items-center gap-3 user-box">
                @auth
                    <!-- Link profile bao gồm logo/avatar và tên user -->
                    <a href="{{ route('profile.show') }}"
                        class="d-flex align-items-center gap-2 text-decoration-none text-light">
                        <!-- logo -->
                        👤
                        <span>{{ auth()->user()->name }}</span>
                    </a>

                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-logout">Đăng xuất</button>
                    </form>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">Register</a>
                @endguest
            </div>

        </div>
    </div>
</nav>