@extends('layouts.master')

@section('content')

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="section-title">📄 Booking của tôi</h3>

        <a href="/tours" class="btn btn-main btn-sm">
            + Đặt thêm tour
        </a>
    </div>

    <div class="card card-custom p-3">

        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tour</th>
                    <th>Người</th>
                    <th>Tiền</th>
                    <th>Trạng thái</th>
                    <th style="width: 170px; text-align: center;">Action</th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $booking)
                    <tr class="fade-in">

                        <!-- TOUR -->
                        <td>
                            <strong>{{ $booking->tour->title }}</strong>
                            <div class="text-muted small">
                                {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') }}
                            </div>
                        </td>

                        <!-- SỐ NGƯỜI -->
                        <td>
                            👥 {{ $booking->bookingDetail->quantity }}
                        </td>

                        <!-- TIỀN -->
                        <td class="text-primary fw-bold">
                            {{ number_format($booking->total_price) }} VNĐ
                        </td>

                        <!-- STATUS -->
                        <td>
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning text-dark">Pending</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>

                        <!-- ACTION (FIX KHÔNG LỆCH) -->
                        <td style="width: 170px;">
                            <div class="d-flex justify-content-center gap-2">

                                <!-- DETAIL -->
                                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-main hover-glow">
                                    Chi tiết
                                </a>

                                <!-- CANCEL -->
                                @if($booking->status == 'pending')
                                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST"
                                        onsubmit="return confirm('Bạn có chắc muốn hủy?')">
                                        @csrf
                                        <button class="btn btn-danger btn-sm">
                                            ❌
                                        </button>
                                    </form>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif

                            </div>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">
                            Không có booking
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-3">
            {{ $bookings->links() }}
        </div>

    </div>

@endsection