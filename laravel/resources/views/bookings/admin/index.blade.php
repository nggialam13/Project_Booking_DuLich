@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-start gap-3 mb-3">
        <div>
            <h2 class="mb-1 text-danger">Admin - Danh sách booking</h2>
            <div class="text-muted">Xác nhận / hủy booking theo trạng thái.</div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Filter trạng thái (nếu muốn) --}}
    <form method="GET" class="row g-2 align-items-end mb-3">
        <div class="col-sm-4 col-md-3">
            <label class="form-label">Lọc theo trạng thái</label>
            <select name="status" class="form-select">
                <option value="">Tất cả</option>
                <option value="pending" @if(($status ?? '') === 'pending') selected @endif>Pending</option>
                <option value="confirmed" @if(($status ?? '') === 'confirmed') selected @endif>Confirmed</option>
                <option value="cancelled" @if(($status ?? '') === 'cancelled') selected @endif>Cancelled</option>
            </select>
        </div>
        <div class="col-sm-3">
            <button class="btn btn-primary">Lọc</button>
            <a href="{{ route('admin.bookings.index') }}" class="btn btn-outline-secondary">Reset</a>
        </div>
    </form>

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>User</th>
                    <th>Tour</th>
                    <th>Ngày đặt</th>
                    <th class="text-end">Tổng tiền</th>
                    <th>Trạng thái</th>
                    <th class="text-end">Hành động</th>
                </tr>
                </thead>
                <tbody>
                @forelse($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->tour->title ?? 'N/A' }}</td>
                        <td>{{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') }}</td>
                        <td class="text-end">{{ number_format($booking->total_price) }} VNĐ</td>
                        <td>
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>
                        <td class="text-end">
                            @if($booking->status == 'pending')
                                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Xác nhận</button>
                                </form>
                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Admin chắc chắn muốn hủy booking này?')">
                                        Hủy
                                    </button>
                                </form>
                            @elseif($booking->status == 'confirmed')
                                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Admin chắc chắn muốn hủy booking này?')">
                                        Hủy
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            Chưa có booking nào.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Phân trang --}}
    <div class="d-flex justify-content-center mt-3">
     {{ $bookings->links() }}
    </div>

</div>

</body>
</html>
@endsection