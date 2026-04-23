@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Admin - Danh sách booking</h2>

        @foreach($bookings as $booking)
            <div style="border:1px solid #000; margin:10px; padding:10px;">
                <p><b>User:</b> {{ $booking->user->name ?? 'N/A' }}</p>
                <p><b>Tour:</b> {{ $booking->tour->title }}</p>
                <p><b>Tổng tiền:</b> {{ number_format($booking->total_price) }} VNĐ</p>
                <p><b>Trạng thái:</b> {{ $booking->status }}</p>
            </div>
        @endforeach

    </div>
@endsection