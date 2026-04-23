<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin Booking</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">

    <h2 class="mb-4 text-danger">Admin - Danh sách booking</h2>

    @foreach($bookings as $booking)
        <div class="card mb-3">
            <div class="card-body">

                <p><b>User:</b> {{ $booking->user->name ?? 'N/A' }}</p>

                <p><b>Tour:</b> {{ $booking->tour->title }}</p>

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

                @if($booking->status == 'pending')
                    <form action="{{ route('admin.bookings.confirm', $booking->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm">Xác nhận</button>
                    </form>

                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-danger btn-sm">Hủy</button>
                    </form>
                @endif

                @if($booking->status == 'confirmed')
                    <form action="{{ route('admin.bookings.cancel', $booking->id) }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">Hủy</button>
                    </form>
                @endif

                @if($booking->status == 'cancelled')
                    <p class="text-danger mt-2">Đã hủy</p>
                @endif

            </div>
        </div>
    @endforeach

</div>

</body>
</html>