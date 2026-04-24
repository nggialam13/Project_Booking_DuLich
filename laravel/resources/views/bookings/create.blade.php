@extends('layouts.master')

@section('content')
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

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-1">Đặt Tour</h2>
            <div class="text-muted">Vui lòng nhập số người để đặt chỗ.</div>
        </div>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">Quay lại</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body">

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label">Tên tour</label>
                        <input type="text" class="form-control" value="{{ $tour->title }}" disabled>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Số chỗ còn</label>
                        <input type="text" class="form-control" value="{{ $tour->available_slots }}" disabled>
                        <div class="form-text">
                            Gợi ý: bạn chỉ có thể đặt tối đa <b>{{ $tour->available_slots }}</b> chỗ.
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label">Giá</label>
                    <input type="text" class="form-control" value="{{ number_format($tour->price) }} VNĐ" disabled>
                </div>

                <div class="mt-3">
                    <label class="form-label">Số người</label>
                    <input
                        type="number"
                        name="quantity"
                        class="form-control @error('quantity') is-invalid @enderror"
                        min="1"
                        max="{{ $tour->available_slots }}"
                        value="{{ old('quantity') }}"
                        placeholder="Nhập số người"
                        required
                    >
                    @error('quantity')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                @if($tour->available_slots == 0)
                    <div class="alert alert-danger">Tour đã hết chỗ</div>
                @endif

                <div class="d-flex gap-2 mt-4">
                    <button class="btn btn-primary"
                            @if($tour->available_slots == 0) disabled @endif>
                        Đặt Tour
                    </button>
                    <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary">Xem booking của tôi</a>
                </div>

            </form>
        </div>
    </div>

</div>

</body>
</html>
@endsection