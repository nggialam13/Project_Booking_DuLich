@extends('layouts.admin')

@section('content')

<h3 class="section-title mb-4">📊 Quản lý Booking</h3>

<div class="card card-custom p-3">
    {{-- FILTER + SEARCH --}}
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="mb-3 d-flex gap-2">

        {{-- SEARCH --}}
        <input
            type="text"
            name="keyword"
            value="{{ request('keyword') }}"
            class="form-control"
            placeholder="🔍 Tìm user hoặc tour..."
            style="width: 250px;"
             onkeyup="this.form.submit()">

        {{-- STATUS --}}
        <select name="status" class="form-select" style="width: 180px;">
            <option value="">-- Tất cả --</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                Pending
            </option>
            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>
                Confirmed
            </option>
            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>
                Cancelled
            </option>
        </select>

        <button class="btn btn-primary">
            Tìm
        </button>

        <a href="{{ route('admin.bookings.index') }}" class="btn btn-secondary">
            Reset
        </a>

    </form>
    <div class="mb-2">
        <strong>Tổng booking:</strong> {{ $bookings->total() }}
    </div>
    <div class="mb-2 text-muted small">
        Hiển thị
        {{ $bookings->firstItem() }} - {{ $bookings->lastItem() }}
        trên {{ $bookings->total() }} booking
    </div>
    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Code</th> {{-- ✅ thêm --}}
                <th>User</th>
                <th>Tour</th>
                <th>Người</th>
                <th>Tiền</th>
                <th>Status</th>
                <th style="width: 140px; text-align: center;">Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($bookings as $booking)
            <tr>
                <td>
                    {{ $loop->iteration + ($bookings->currentPage() - 1) * $bookings->perPage() }}
                </td>
                {{-- ✅ BOOKING CODE --}}
                <td>
                    <span class="badge bg-dark">
                        {{ $booking->booking_code }}
                    </span>
                </td>

                {{-- ✅ NULL SAFE --}}
                <td>{{ optional($booking->user)->name ?? 'N/A' }}</td>

                <td>{{ optional($booking->tour)->title ?? 'N/A' }}</td>

                {{-- ✅ NULL SAFE quantity --}}
                <td>
                    👥 {{ (int) optional($booking->bookingDetail)->quantity }}
                </td>

                <td class="text-primary fw-bold">
                    {{ number_format($booking->total_price, 0, ',', '.') }} VNĐ
                </td>

                <td>
                    @if($booking->status === 'pending')
                    <span class="badge bg-warning">Pending</span>
                    @elseif($booking->status === 'confirmed')
                    <span class="badge bg-success">Confirmed</span>
                    @else
                    <span class="badge bg-danger">Cancelled</span>
                    @endif
                </td>

                <td style="width: 140px;">
                    <div class="d-flex justify-content-center gap-2">

                        {{-- ✅ CHỈ pending mới action --}}
                        @if($booking->status === 'pending')

                        {{-- CONFIRM --}}
                        <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-success btn-sm">
                                ✔
                            </button>
                        </form>

                        {{-- CANCEL --}}
                        <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                            @csrf
                            <button class="btn btn-danger btn-sm">
                                ✖
                            </button>
                        </form>

                        @else
                        <span class="text-muted small">—</span>
                        @endif

                    </div>
                </td>

            </tr>
            @endforeach
        </tbody>

    </table>

    {{-- PAGINATION --}}
    <div class="d-flex justify-content-center pt-3 border-top">
        {{ $bookings->appends(request()->query())->links() }}
    </div>

</div>

@endsection