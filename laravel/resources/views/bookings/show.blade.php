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

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <div class="d-flex justify-content-between align-items-start gap-3 mb-4">
                <div>
                    <h3 class="mb-1">Chi tiết booking</h3>
                    <div class="text-muted">
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

            <div class="row g-3">
                <div class="col-md-8">
                    <div class="p-3 bg-light rounded">
                        <div class="fw-bold mb-1">Tour</div>
                        <div class="fs-5">{{ $booking->tour->title }}</div>
                        <div class="text-muted">Giá: {{ number_format($booking->tour->price) }} VNĐ</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 border rounded">
                        <div class="text-muted">Số người</div>
                        <div class="fs-4 fw-bold">{{ (int) optional($booking->bookingDetail)->quantity }}</div>
                        <hr class="my-2">
                        <div class="text-muted">Tổng tiền</div>
                        <div class="fs-5 fw-bold">{{ number_format($booking->total_price) }} VNĐ</div>
                    </div>
                </div>
            </div>

            {{-- Cancel chuẩn: chỉ hiện khi pending/confirmed --}}
            @if(in_array($booking->status, ['pending', 'confirmed']))
                <form action="{{ route('bookings.cancel', $booking->id) }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger mt-4"
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