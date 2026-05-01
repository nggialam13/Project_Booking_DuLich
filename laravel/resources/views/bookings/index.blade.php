@extends('layouts.master')

@section('content')

<div class="container main-content my-5">

    <!-- HEADER -->
    <div class="booking-header d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="booking-title">📋 Danh sách booking</h2>
            <p class="text-muted mb-0">Quản lý các tour bạn đã đặt</p>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm shadow-sm">
            ← Quay lại
        </a>
    </div>

    <!-- ALERT -->
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- EMPTY -->
    @if($bookings->isEmpty())
        <div class="empty-box text-center">
            <h5>📭 Bạn chưa có booking nào</h5>
            <a href="/tours" class="btn btn-main mt-3">Khám phá tour</a>
        </div>
    @endif

    <!-- LIST -->
    <div class="row g-4">

        @foreach($bookings as $booking)
        <div class="col-md-6">

            <div class="booking-card">

                <!-- TOP -->
                <div class="d-flex justify-content-between align-items-start">

                    <div>
                        <h5 class="tour-name">
                            {{ $booking->tour->title }}
                        </h5>

                        <div class="booking-date">
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

                <!-- BODY -->
                <div class="booking-info">
                    <div class="price">
                        💰 {{ number_format($booking->total_price) }} VNĐ
                    </div>
                </div>

                <!-- ACTION -->
                <div class="booking-actions">

                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-view">
                        Xem chi tiết
                    </a>

                    @if(in_array($booking->status, ['pending', 'confirmed']))
                    <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-cancel"
                            onclick="return confirm('Bạn chắc chắn muốn hủy booking này?')">
                            Hủy
                        </button>
                    </form>
                    @endif

                </div>

            </div>

        </div>
        @endforeach

    </div>

    <!-- PAGINATION -->
    <div class="mt-5 d-flex justify-content-center">
        {{ $bookings->links() }}
    </div>

</div>

@endsection