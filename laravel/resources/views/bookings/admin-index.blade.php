@extends('layouts.admin')

@section('content')

<h3 class="section-title mb-4">📊 Quản lý Booking</h3>

<div class="card card-custom p-3">

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
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
        {{ $bookings->links() }}
    </div>

</div>

@endsection