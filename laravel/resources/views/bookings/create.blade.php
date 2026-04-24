@extends('layouts.master')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">

        <div class="card card-custom p-4">
            <h4 class="text-center mb-4 section-title">
                🎟 Đặt Tour
            </h4>

            <!-- Tour Info -->
            <div class="mb-3 p-3 rounded bg-light">
                <strong>{{ $tour->title }}</strong>
                <div class="text-muted small">
                    📍 {{ $tour->location }}
                </div>
                <div class="text-primary fw-bold">
                    {{ number_format($tour->price) }} VNĐ
                </div>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                <div class="mb-3">
                    <label class="form-label">Số lượng người</label>
                    <input type="number"
                           name="quantity"
                           class="form-control"
                           min="1"
                           required>
                </div>

                <button class="btn btn-main w-100">
                    🚀 Đặt ngay
                </button>
            </form>

        </div>

    </div>
</div>
@endsection