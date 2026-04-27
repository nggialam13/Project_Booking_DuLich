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
                    {{ number_format($tour->price) }} VNĐ / người
                </div>

                <div class="text-success small">
                    🟢 Còn {{ $tour->available_slots }} chỗ
                </div>
            </div>

            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <input type="hidden" name="tour_id" value="{{ $tour->id }}">

                <!-- SỐ NGƯỜI -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Số lượng người</label>
                    <input type="number"
                           name="quantity"
                           class="form-control"
                           min="1"
                           max="{{ $tour->available_slots }}"
                           value="1"
                           required>
                </div>

                <!-- TỔNG TIỀN (UI) -->
                <div class="mb-3">
                    <label class="form-label fw-semibold">Tổng tiền</label>
                    <input type="text"
                           id="totalPrice"
                           class="form-control"
                           disabled>
                </div>

                <!-- BUTTON -->
                <button class="btn btn-main w-100">
                    🚀 Đặt ngay
                </button>
            </form>

        </div>

    </div>
</div>

<!-- SCRIPT TÍNH TIỀN -->
<script>
    const price = {{ $tour->price }};
    const qtyInput = document.querySelector('[name="quantity"]');
    const total = document.getElementById('totalPrice');

    function updateTotal() {
        total.value = (price * qtyInput.value).toLocaleString() + ' VNĐ';
    }

    qtyInput.addEventListener('input', updateTotal);
    updateTotal();
</script>

@endsection