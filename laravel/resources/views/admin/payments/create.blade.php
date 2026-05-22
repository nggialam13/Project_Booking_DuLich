@extends('layouts.admin')

@section('title', 'Tạo thanh toán')

@section('content')
<div class="container-fluid px-0" style="max-width: 920px;">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-file-invoice-dollar me-2"></i>Tạo thanh toán</h2>
            <p class="text-muted mb-0">Chọn booking, xác nhận số tiền và phương thức thanh toán</p>
        </div>
        <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-1"></i> Danh sách
        </a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.payments.store') }}" method="POST" id="admin-payment-form">
        @csrf

        <div class="card card-admin mb-3">
            <div class="card-admin-header">
                <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Booking</h5>
            </div>
            <div class="card-admin-body">
                <label for="booking_id" class="form-label fw-semibold">Chọn đặt chỗ <span class="text-danger">*</span></label>
                <select name="booking_id" id="booking_id" class="form-select form-select-lg" required>
                    <option value="">— Chọn booking —</option>
                    @foreach($bookings as $b)
                        <option
                            value="{{ $b->id }}"
                            data-amount="{{ (int) $b->total_price }}"
                            data-code="{{ $b->booking_code }}"
                            data-tour="{{ $b->tour->title ?? 'Tour' }}"
                            data-status="{{ $b->status }}"
                            @selected(old('booking_id') == $b->id)
                        >
                            {{ $b->booking_code }} — {{ number_format((int) $b->total_price, 0, ',', '.') }} đ
                            @if($b->tour)
                                ({{ str($b->tour->title)->limit(35) }})
                            @endif
                        </option>
                    @endforeach
                </select>
                <small class="text-muted d-block mt-2">Số tiền mặc định lấy theo tổng giá booking; bạn có thể chỉnh lại bên dưới nếu cần.</small>
            </div>
        </div>

        <div class="card card-admin mb-3 border-primary border-opacity-25" style="border-width: 2px;">
            <div class="card-admin-header" style="background: linear-gradient(135deg, #e7f1ff, #f8f9fa);">
                <h5 class="mb-0"><i class="fas fa-coins me-2"></i>Số tiền thanh toán</h5>
            </div>
            <div class="card-admin-body">
                <div class="rounded-3 p-4 mb-3 text-center" style="background: linear-gradient(135deg, #0f3460 0%, #16213e 100%); color: #fff;">
                    <div class="small text-white-50 mb-1">Số tiền hiển thị (VNĐ)</div>
                    <div id="amount-display" class="display-6 fw-bold" style="letter-spacing: 0.02em;">0 đ</div>
                    <div id="booking-hint" class="small mt-2 text-white-50 d-none"></div>
                </div>
                <label for="amount" class="form-label fw-semibold">Nhập số tiền (số nguyên) <span class="text-danger">*</span></label>
                <div class="input-group input-group-lg">
                    <input type="number" name="amount" id="amount" class="form-control" min="0" step="1"
                           value="{{ old('amount') }}" required placeholder="VD: 5000000">
                    <span class="input-group-text">đ</span>
                </div>
            </div>
        </div>

        <div class="card card-admin mb-4">
            <div class="card-admin-header">
                <h5 class="mb-0"><i class="fas fa-wallet me-2"></i>Phương thức thanh toán</h5>
            </div>
            <div class="card-admin-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="payment-method-card w-100 mb-0">
                            <input type="radio" name="payment_method" value="cash" class="d-none" {{ old('payment_method', 'cash') === 'cash' ? 'checked' : '' }} required>
                            <span class="d-block border rounded-3 p-3 h-100 text-center method-card-inner">
                                <i class="fas fa-money-bill-wave fa-2x text-success mb-2"></i>
                                <span class="d-block fw-semibold">Tiền mặt</span>
                                <small class="text-muted">Ghi nhận đã thu, xác nhận booking</small>
                            </span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="payment-method-card w-100 mb-0">
                            <input type="radio" name="payment_method" value="momo" class="d-none" {{ old('payment_method') === 'momo' ? 'checked' : '' }}>
                            <span class="d-block border rounded-3 p-3 h-100 text-center method-card-inner">
                                <i class="fas fa-mobile-screen-button fa-2x mb-2" style="color:#d82d8b;"></i>
                                <span class="d-block fw-semibold">MoMo</span>
                                <small class="text-muted">Luồng xử lý demo (redirect)</small>
                            </span>
                        </label>
                    </div>
                    <div class="col-md-4">
                        <label class="payment-method-card w-100 mb-0">
                            <input type="radio" name="payment_method" value="vnpay" class="d-none" {{ old('payment_method') === 'vnpay' ? 'checked' : '' }}>
                            <span class="d-block border rounded-3 p-3 h-100 text-center method-card-inner">
                                <i class="fas fa-qrcode fa-2x text-primary mb-2"></i>
                                <span class="d-block fw-semibold">VNPay</span>
                                <small class="text-muted">Luồng xử lý demo (redirect)</small>
                            </span>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex flex-wrap gap-2">
            <button type="submit" class="btn btn-admin btn-admin-primary btn-lg px-5">
                <i class="fas fa-check me-1"></i> Lưu thanh toán
            </button>
            <a href="{{ route('admin.payments.index') }}" class="btn btn-outline-secondary btn-lg">Hủy</a>
        </div>
    </form>
</div>

<style>
    .payment-method-card input:checked + .method-card-inner {
        border-color: #0dcaf0 !important;
        background: rgba(13, 202, 240, 0.12);
        box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.25);
    }
    .payment-method-card .method-card-inner {
        cursor: pointer;
        transition: all 0.2s ease;
        border: 2px solid #e9ecef !important;
    }
    .payment-method-card:hover .method-card-inner {
        border-color: #0d6efd !important;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const bookingSelect = document.getElementById('booking_id');
        const amountInput = document.getElementById('amount');
        const amountDisplay = document.getElementById('amount-display');
        const bookingHint = document.getElementById('booking-hint');

        function formatVnd(n) {
            const num = Number(n);
            if (!Number.isFinite(num)) return '0 đ';
            return new Intl.NumberFormat('vi-VN').format(Math.round(num)) + ' đ';
        }

        function refreshDisplay() {
            amountDisplay.textContent = formatVnd(amountInput.value || 0);
        }

        function updateBookingHint() {
            const opt = bookingSelect.selectedOptions[0];
            if (!opt || !opt.value) {
                bookingHint.classList.add('d-none');
                return;
            }
            const code = opt.getAttribute('data-code') || '';
            const tour = opt.getAttribute('data-tour') || '';
            const status = opt.getAttribute('data-status') || '';
            bookingHint.textContent = [code, tour, status ? 'Trạng thái: ' + status : ''].filter(Boolean).join(' · ');
            bookingHint.classList.remove('d-none');
        }

        bookingSelect.addEventListener('change', function () {
            const opt = bookingSelect.selectedOptions[0];
            if (opt && opt.value) {
                amountInput.value = opt.getAttribute('data-amount') || '';
            }
            updateBookingHint();
            refreshDisplay();
        });

        amountInput.addEventListener('input', refreshDisplay);

        updateBookingHint();
        refreshDisplay();
    });
</script>
@endsection
