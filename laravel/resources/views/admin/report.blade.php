@extends('layouts.admin')

@section('content')

<div class="container py-4">

    <!-- 🧠 TITLE -->
    <h2 class="mb-4">📊 Report & Thống kê</h2>
    <!-- 📊 DASHBOARD -->
    <div class="row mb-4">



    </div>
    <!-- 🔎 FILTER -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">

                <div class="col-md-3">
                    <label class="form-label">Từ ngày</label>
                    <input type="date" name="from"
                        value="{{ request('from') }}"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Đến ngày</label>
                    <input type="date" name="to"
                        value="{{ request('to') }}"
                        class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Sắp xếp</label>
                    <select name="sort" class="form-control">
                        <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>
                            Mới → Cũ
                        </option>
                        <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>
                            Cũ → Mới
                        </option>
                    </select>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button class="btn btn-primary w-100">🔍 Lọc dữ liệu</button>
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <a href="/admin/report" class="btn btn-secondary w-100">Reset</a>
                </div>

            </form>
        </div>
    </div>

    <!-- 📄 TABLE BOOKING -->
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            📋 Danh sách Booking
        </div>

        <div class="table-responsive">
            <table class="table table-hover mb-0">

                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tour</th>
                        <th>Chi phí</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($bookings as $b)
                    <tr>
                        <td>#{{ $b->id }}</td>
                        <td>{{ $b->user->name ?? 'N/A' }}</td>
                        <td>{{ $b->tour->title ?? 'N/A' }}</td>
                        <td>{{ number_format($b->total_price, 0, ',', '.') }} đ</td>
                        <td>
                            <span class="badge 
                                @if($b->status == 'confirmed') bg-success
                                @elseif($b->status == 'pending') bg-warning
                                @else bg-danger
                                @endif">
                                {{ $b->status }}
                            </span>
                        </td>
                        <td>{{ $b->created_at->format('d/m/Y') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted">
                            Không có dữ liệu
                        </td>
                    </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <!-- 📄 PAGINATION -->
        <div class="card-footer">
            <div class="d-flex justify-content-between align-items-center">

                <small>
                    Hiển thị {{ $bookings->firstItem() ?? 0 }}
                    - {{ $bookings->lastItem() ?? 0 }}
                    / {{ $bookings->total() }} bản ghi
                </small>

                {{ $bookings->links() }}
            </div>
        </div>
        <!-- 📊 Tổng doanh thu -->
        <div class="col-md-3">
            <div class="card shadow border-0">

                <div class="card-body text-center">

                    <h6 class="text-muted">
                        Tổng doanh thu
                    </h6>

                    <h3 class="fw-bold text-success">
                        {{ number_format($totalRevenue, 0, ',', '.') }} đ
                    </h3>

                </div>

            </div>
        </div>
    </div>

</div>

@endsection