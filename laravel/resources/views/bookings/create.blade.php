@extends('layouts.master')

@section('content')

<div class="container main-content my-5">

    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="create-title">🧳 Đặt Tour</h2>
            <p class="text-muted mb-0">Nhập thông tin để hoàn tất booking</p>
        </div>

        <a href="{{ url()->previous() }}" class="btn btn-light btn-sm shadow-sm">
            ← Quay lại
        </a>
    </div>

    <!-- ALERT -->
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif

    <!-- CARD -->
    <div class="booking-create-card">

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <input type="hidden" name="tour_id" value="{{ $tour->id }}">

            <div class="row g-4">

                <!-- LEFT -->
                <div class="col-md-8">

                    <div class="form-group">
                        <label>Tên tour</label>
                        <input type="text" class="form-control input-pro"
                               value="{{ $tour->title }}" disabled>
                    </div>

                    <div class="form-group mt-3">
                        <label>Giá</label>
                        <input type="text" class="form-control input-pro"
                               value="{{ number_format($tour->price) }} VNĐ" disabled>
                    </div>

                    <div class="form-group mt-3">
                        <label>Số người</label>
                        <input
                            type="number"
                            name="quantity"
                            class="form-control input-pro @error('quantity') is-invalid @enderror"
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

                </div>

                <!-- RIGHT -->
                <div class="col-md-4">

                    <div class="booking-summary">

                        <h5>📊 Thông tin</h5>

                        <div class="summary-item">
                            <span>Chỗ còn</span>
                            <strong>{{ $tour->available_slots }}</strong>
                        </div>

                        <div class="summary-item">
                            <span>Giá / người</span>
                            <strong>{{ number_format($tour->price) }}</strong>
                        </div>

                        @if($tour->available_slots == 0)
                            <div class="alert alert-danger mt-3 mb-0">
                                Tour đã hết chỗ
                            </div>
                        @endif

                    </div>

                </div>

            </div>

            <!-- ACTION -->
            <div class="d-flex gap-3 mt-4">

                <button class="btn btn-book"
                        @if($tour->available_slots == 0) disabled @endif>
                    🚀 Đặt Tour
                </button>

                <a href="{{ route('bookings.index') }}" class="btn btn-my-booking">
                    📋 Booking của tôi
                </a>

            </div>

        </form>

    </div>

</div>

@endsection