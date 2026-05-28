@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')

    <style>
        body {
            background: #f4f7fb;
        }

        .dashboard-header {
            background: linear-gradient(135deg, #4f46e5, #7c3aed);
            border-radius: 24px;
            padding: 35px;
            color: white;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .dashboard-header h1 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .stat-card {
            border: none;
            border-radius: 22px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
            position: relative;
        }

        .stat-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card .card-body {
            padding: 25px;
        }

        .stat-icon {
            font-size: 42px;
            opacity: 0.2;
            position: absolute;
            right: 20px;
            top: 20px;
        }

        .stat-title {
            font-size: 15px;
            opacity: 0.9;
            margin-bottom: 8px;
        }

        .stat-number {
            font-size: 34px;
            font-weight: bold;
        }

        .menu-card {
            border: none;
            border-radius: 22px;
            transition: 0.3s;
            overflow: hidden;
            box-shadow: 0 5px 18px rgba(0, 0, 0, 0.08);
        }

        .menu-card:hover {
            transform: translateY(-5px);
        }

        .menu-card .card-body {
            padding: 30px;
        }

        .menu-icon {
            width: 70px;
            height: 70px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        .menu-card h5 {
            font-weight: 700;
            margin-bottom: 10px;
        }

        .menu-card p {
            color: #6b7280;
            min-height: 45px;
        }

        .dashboard-btn {
            border-radius: 12px;
            padding: 10px 18px;
            font-weight: 600;
        }
    </style>

    <div class="container-fluid px-4">

        {{-- Header --}}
        <div class="dashboard-header">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h1>Xin chào Admin 👋</h1>
                    <p class="mb-0">
                        Chào mừng quay trở lại hệ thống quản lý Tour & Booking
                    </p>
                </div>

                <div class="col-md-4 text-md-end mt-3 mt-md-0">
                    <h5>{{ now()->format('d/m/Y') }}</h5>
                </div>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="row g-4 mb-4">

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #3b82f6, #2563eb);">
                    <div class="card-body">
                        <i class="fas fa-users stat-icon"></i>

                        <div class="stat-title">
                            Tổng người dùng
                        </div>

                        <div class="stat-number">
                            {{ \App\Models\User::count() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #10b981, #059669);">
                    <div class="card-body">
                        <i class="fas fa-map-marked-alt stat-icon"></i>

                        <div class="stat-title">
                            Tổng Tours
                        </div>

                        <div class="stat-number">
                            {{ \App\Models\Tour::count() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #f59e0b, #d97706);">
                    <div class="card-body">
                        <i class="fas fa-calendar-check stat-icon"></i>

                        <div class="stat-title">
                            Tổng Bookings
                        </div>

                        <div class="stat-number">
                            {{ \App\Models\Booking::count() }}
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6">
                <div class="card stat-card text-white" style="background: linear-gradient(135deg, #8b5cf6, #7c3aed);">
                    <div class="card-body">
                        <i class="fas fa-wallet stat-icon"></i>

                        <div class="stat-title">
                            Doanh thu
                        </div>

                        <div class="stat-number" style="font-size: 26px">
                            {{ number_format(\App\Models\Payment::where('status', 'paid')->sum('amount')) }}đ
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Menu --}}
        <div class="row g-4">

            {{-- Users --}}
            <div class="col-xl-3 col-md-6">
                <div class="card menu-card h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="menu-icon text-primary bg-primary bg-opacity-10">
                            <i class="fas fa-users"></i>
                        </div>

                        <h5>Quản lý người dùng</h5>

                        <p>
                            Xem, thêm, sửa và quản lý tài khoản người dùng.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('admin.users') }}" class="btn btn-primary dashboard-btn w-100">
                                Truy cập
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Tours --}}
            <div class="col-xl-3 col-md-6">
                <div class="card menu-card h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="menu-icon text-success bg-success bg-opacity-10">
                            <i class="fas fa-map-marked-alt"></i>
                        </div>

                        <h5>Quản lý Tours</h5>

                        <p>
                            Quản lý tour du lịch và trạng thái hoạt động.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('tours.index') }}" class="btn btn-success dashboard-btn w-100">
                                Truy cập
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Bookings --}}
            <div class="col-xl-3 col-md-6">
                <div class="card menu-card h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="menu-icon text-warning bg-warning bg-opacity-10">
                            <i class="fas fa-calendar-check"></i>
                        </div>

                        <h5>Quản lý Bookings</h5>

                        <p>
                            Theo dõi, xác nhận và hủy booking khách hàng.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('admin.bookings.index') }}"
                                class="btn btn-warning dashboard-btn text-white w-100">
                                Truy cập
                            </a>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Payments --}}
            <div class="col-xl-3 col-md-6">
                <div class="card menu-card h-100">
                    <div class="card-body d-flex flex-column">

                        <div class="menu-icon text-danger bg-danger bg-opacity-10">
                            <i class="fas fa-credit-card"></i>
                        </div>

                        <h5>Quản lý Payments</h5>

                        <p>
                            Kiểm tra thanh toán và thống kê doanh thu hệ thống.
                        </p>

                        <div class="mt-auto">
                            <a href="{{ route('payment.index') }}" class="btn btn-danger dashboard-btn w-100">
                                Truy cập
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

@endsection