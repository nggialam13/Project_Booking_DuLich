@extends('layouts.master')

@section('content')

<div class="container main-content my-5">

    @if(session('error'))
    <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <div class="booking-detail-card">

        <!-- HEADER -->
        <div class="booking-detail-header d-flex justify-content-between align-items-start">

            <div>
                <h3 class="title">📄 Chi tiết booking</h3>
                <div class="mt-1 text-muted">
                    Mã booking: <strong>{{ $booking->booking_code }}</strong>
                </div>
                <div class="date">
                    📅 {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') }}
                </div>
            </div>

            <!-- STATUS -->
            @if($booking->status == 'pending')
            <span class="status-badge pending">Pending</span>
            @elseif($booking->status == 'confirmed')
            <span class="status-badge success">Confirmed</span>
            @else
            <span class="status-badge cancel">Cancelled</span>
            @endif

        </div>

        <!-- CONTENT -->
        <div class="row g-4 mt-2">

            <!-- TOUR -->
            <div class="col-md-8">
                <div class="detail-box tour-box">
                    <div class="detail-label">Tour</div>
                    <div class="tour-name">{{ $booking->tour->title }}</div>
                    <div class="tour-price">
                        💰 {{ number_format($booking->tour->price) }} VNĐ
                    </div>
                </div>
            </div>

            <!-- INFO -->
            <div class="col-md-4">
                <div class="detail-box info-box">

                    <div class="info-item">
                        <span>Số người</span>
                        <strong>{{ (int) optional($booking->bookingDetail)->quantity }}</strong>
                    </div>

                    <hr>

                    <div class="info-item">
                        <span>Tổng tiền</span>
                        <strong class="total">
                            {{ number_format($booking->total_price) }} VNĐ
                        </strong>
                    </div>

                </div>
            </div>

        </div>

        <!-- ACTION -->
        <div class="action-box mt-4 d-flex flex-wrap gap-2 align-items-center">

            @if($booking->status !== 'cancelled')
                @include('bookings.partials.payment-actions', ['booking' => $booking])
            @endif

            @if(in_array($booking->status, ['pending', 'confirmed']))
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                @csrf
                <button class="btn btn-cancel"
                    onclick="return confirm('Bạn chắc chắn muốn hủy?')">
                    ❌ Hủy booking
                </button>
            </form>
            @endif

            <a href="{{ route('bookings.index') }}" class="btn btn-back d-inline-block">
                ← Quay lại
            </a>

        </div>

    </div>

</div>

@endsection