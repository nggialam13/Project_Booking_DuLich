@extends('layouts.master')

@section('content')

    <h3 class="section-title mb-4">📄 Booking của tôi</h3>

    <div class="card card-custom p-3">

        <table class="table align-middle">
            <thead class="table-light">
                <tr>
                    <th>Tour</th>
                    <th>Tiền</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>

            <tbody>
                @forelse($bookings as $booking)
                    <tr>

                        <td>
                            <strong>{{ $booking->tour->title }}</strong>
                            <div class="text-muted small">
                                {{ $booking->booking_date }}
                            </div>
                        </td>

                        <td class="text-primary fw-bold">
                            {{ number_format($booking->total_price) }} VNĐ
                        </td>

                        <td>
                            @if($booking->status == 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($booking->status == 'confirmed')
                                <span class="badge bg-success">Confirmed</span>
                            @else
                                <span class="badge bg-danger">Cancelled</span>
                            @endif
                        </td>

                        <td>
                            <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-sm btn-main">
                                Chi tiết
                            </a>
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted">
                            Không có booking
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>

    </div>

@endsection