<nav class="navbar navbar-expand-lg navbar-dark bg-main shadow-sm">
    <div class="container">
        <!-- Brand -->
        <a class="navbar-brand fw-bold fs-4" href="/">
            🌍 TravelPro
        </a>

        <!-- Nút toggle cho mobile (nếu cần) -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Collapsible content -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('tours.index') ?? '/tours' }}">Tours</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('bookings.index') ?? '/bookings' }}">Booking</a>
                </li>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a class="nav-link" href="/admin/dashboard">Admin</a>
                        </li>
                    @endif
                @endauth
            </ul>

            <!-- Phần bên phải -->
            <div class="d-flex gap-2 align-items-center">
                @auth
                    <!-- Dropdown profile (giữ từ HEAD) -->
                    <div class="dropdown">
                        <button class="btn btn-light btn-sm dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown">
                            {{ Auth::user()->name }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile') }}">Hồ sơ của tôi</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">Đăng xuất</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endauth

                @guest
                    <a href="{{ route('login') }}" class="btn btn-light btn-sm">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-light btn-sm">Register</a>
                @endguest
            </div>
        </div>
    </div>
</nav>