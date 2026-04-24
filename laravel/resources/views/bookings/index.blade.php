@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Danh sách Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Danh sách booking</h2>
            <div class="text-muted">Quản lý các booking bạn đã đặt.</div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($bookings->isEmpty())
        <div class="alert alert-info mb-0">
            Bạn chưa có booking nào. Hãy chọn tour và đặt ngay.
        </div>
    @endif

    @foreach($bookings as $booking)
        <div class="card mb-3 shadow-sm border-0">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start gap-3">
                    <div>
                        <h5 class="card-title mb-1">{{ $booking->tour->title }}</h5>
                        <div class="text-muted small">
                            Ngày đặt: {{ \Carbon\Carbon::parse($booking->booking_date)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="text-end">
                        @if($booking->status == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($booking->status == 'confirmed')
                            <span class="badge bg-success">Confirmed</span>
                        @else
                            <span class="badge bg-danger">Cancelled</span>
                        @endif
                    </div>
                </div>

                <div class="mt-3">
                    <div><b>Tổng tiền:</b> {{ number_format($booking->total_price) }} VNĐ</div>
                </div>

                <div class="d-flex gap-2 mt-3">
                    <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                        Xem chi tiết
                    </a>

                    @if(in_array($booking->status, ['pending', 'confirmed']))
                        <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button class="btn btn-outline-danger btn-sm"
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

</body>
</html>
@endsection