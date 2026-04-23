@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Chi tiết booking</h2>

        <p><b>Tour:</b> {{ $booking->tour->title }}</p>

        <p><b>Giá:</b> {{ number_format($booking->tour->price) }} VNĐ</p>

        <p><b>Số người:</b> {{ $booking->bookingDetail->quantity }}</p>

        <p><b>Tổng tiền:</b> {{ number_format($booking->total_price) }} VNĐ</p>

        <p><b>Trạng thái:</b> {{ $booking->status }}</p>
        @if($booking->status != 'cancelled')
            <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                @csrf
                <button type="submit" onclick="return confirm('Bạn chắc chắn muốn hủy?')">
                    Hủy booking
                </button>
            </form>
        @endif

        <a href="{{ route('bookings.index') }}">Quay lại</a>
    </div>
@endsection