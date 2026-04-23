<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đặt Tour</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">

    <h2 class="mb-4">Đặt Tour</h2>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <div class="card-body">

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                <div class="mb-3">
                    <label class="form-label">Tên tour</label>
                    <input type="text" class="form-control" value="{{ $tour->title }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Giá</label>
                    <input type="text" class="form-control" value="{{ number_format($tour->price) }} VNĐ" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số chỗ còn</label>
                    <input type="text" class="form-control" value="{{ $tour->available_slots }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số người</label>
                    <input type="number" name="quantity" class="form-control" min="1" required>
                </div>

                @if($tour->available_slots == 0)
                    <div class="alert alert-danger">Tour đã hết chỗ</div>
                @endif

                <button class="btn btn-primary">Đặt Tour</button>

            </form>
        </div>
    </div>

</div>

</body>
</html>