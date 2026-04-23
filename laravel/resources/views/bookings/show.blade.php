<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">

    <div class="card">
        <div class="card-body">

            <h3 class="mb-4">Chi tiết booking</h3>

            <p><b>Tour:</b> {{ $booking->tour->title }}</p>

            <p><b>Giá:</b> {{ number_format($booking->tour->price) }} VNĐ</p>

            <p><b>Số người:</b> {{ $booking->bookingDetail->quantity }}</p>

            <p><b>Tổng tiền:</b> {{ number_format($booking->total_price) }} VNĐ</p>

            <p><b>Trạng thái:</b>
                @if($booking->status == 'pending')
                    <span class="badge bg-warning text-dark">Pending</span>
                @elseif($booking->status == 'confirmed')
                    <span class="badge bg-success">Confirmed</span>
                @else
                    <span class="badge bg-danger">Cancelled</span>
                @endif
            </p>

            @if($booking->status != 'cancelled')
                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-danger"
                        onclick="return confirm('Bạn chắc chắn muốn hủy?')">
                        Hủy booking
                    </button>
                </form>
            @endif

            <a href="{{ route('bookings.index') }}" class="btn btn-secondary mt-3">
                Quay lại
            </a>

        </div>
    </div>

</div>

</body>
</html>