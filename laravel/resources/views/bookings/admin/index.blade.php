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

            {{-- Nếu pending --}}
            @if($booking->status == 'pending')
                <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit">Xác nhận</button>
                </form>

                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit">Hủy</button>
                </form>
            @endif

            {{-- Nếu confirmed --}}
            @if($booking->status == 'confirmed')
                <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <button type="submit">Hủy</button>
                </form>
            @endif

            {{-- Nếu cancelled --}}
            @if($booking->status == 'cancelled')
                <p style="color:red">Đã hủy</p>
            @endif
            </div>
        @endforeach

    </div>
@endsection