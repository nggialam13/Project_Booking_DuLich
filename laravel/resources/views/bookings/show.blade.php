@extends('layouts.master')

@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card card-custom p-4">

            <h4 class="mb-4 section-title">
                📑 Chi tiết Booking
            </h4>

            <div class="mb-3">
                <strong>Tour:</strong>
                <div>{{ $booking->tour->title }}</div>
            </div>

            <div class="mb-3">
                <strong>Số lượng:</strong>
                <div>{{ $booking->bookingDetail->quantity }}</div>
            </div>

            <div class="mb-3">
                <strong>Giá:</strong>
                <div class="text-primary">
                    {{ number_format($booking->bookingDetail->price) }} VNĐ
                </div>
            </div>

            <div class="mb-3">
                <strong>Tổng tiền:</strong>
                <div class="fs-5 fw-bold text-success">
                    {{ number_format($booking->total_price) }} VNĐ
                </div>
            </div>

            <div class="mb-3">
                <strong>Trạng thái:</strong><br>

                @if($booking->status == 'pending')
                    <span class="badge bg-warning">Pending</span>
                @elseif($booking->status == 'confirmed')
                    <span class="badge bg-success">Confirmed</span>
                @else
                    <span class="badge bg-danger">Cancelled</span>
                @endif
            </div>

        </div>

    </div>
</div>

@endsection