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

    <h2 class="mb-4">Danh sách booking</h2>

    @foreach($bookings as $booking)
        <div class="card mb-3">
            <div class="card-body">

                <h5 class="card-title">{{ $booking->tour->title }}</h5>

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

                <a href="{{ route('bookings.show', $booking->id) }}" class="btn btn-outline-primary btn-sm">
                    Xem chi tiết
                </a>

            </div>
        </div>
    @endforeach

</div>

</body>
</html>