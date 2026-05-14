@extends('layouts.master')

@section('content')
<div class="container py-4" style="max-width: 920px;">
    <div class="payment-card">
        <div class="payment-header">
            <h2><i class="fas fa-file-invoice-dollar me-2"></i>Thanh toán booking</h2>
            <p class="mb-0">Kiểm tra số tiền và chọn phương thức thanh toán</p>
        </div>
        <div class="payment-body">
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0 ps-3">
                        @foreach($errors->all() as $err)
                            <li>{{ $err }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <small class="text-muted d-block mb-1">Mã booking</small>
                        <strong class="fs-5">{{ $booking->booking_code ?? ('#'.$booking->id) }}</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <small class="text-muted d-block mb-1">Trạng thái booking</small>
                        <strong class="fs-6 text-capitalize">{{ $booking->status ?? 'pending' }}</strong>
                    </div>
                </div>
                <div class="col-12">
                    <div class="payment-amount-banner text-center text-white rounded-3 py-4 px-3">
                        <div class="small text-white-50 mb-1">Số tiền thanh toán</div>
                        <div class="display-6 fw-bold">{{ number_format($amount, 0, ',', '.') }} đ</div>
                        @if($booking->tour)
                            <div class="small mt-2 text-white-50">{{ $booking->tour->title }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <form action="{{ route('payment.store') }}" method="POST">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="amount" value="{{ $amount }}">

                <h3 class="h5 fw-bold mb-3" style="color:#1a1a2e;">
                    <i class="fas fa-wallet me-2 text-primary"></i>Chọn phương thức
                </h3>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="user-payment-method w-100 mb-0">
                            <input type="radio" name="method" value="momo" class="d-none" checked>
                            <span class="user-payment-method-inner d-block border rounded-3 p-4 h-100 text-center">
                                <i class="fas fa-mobile-screen-button fa-2x mb-2" style="color:#d82d8b;"></i>
                                <span class="d-block fw-bold">MoMo</span>
                                <small class="text-muted">Luồng demo — xác nhận nhanh</small>
                            </span>
                        </label>
                    </div>
                    <div class="col-md-6">
                        <label class="user-payment-method w-100 mb-0">
                            <input type="radio" name="method" value="vnpay" class="d-none">
                            <span class="user-payment-method-inner d-block border rounded-3 p-4 h-100 text-center">
                                <i class="fas fa-qrcode fa-2x text-primary mb-2"></i>
                                <span class="d-block fw-bold">VNPay</span>
                                <small class="text-muted">Luồng demo — xác nhận nhanh</small>
                            </span>
                        </label>
                    </div>
                </div>

                <div class="alert alert-warning border-0 small mb-4" style="border-radius:12px;">
                    <i class="fas fa-info-circle me-1"></i>
                    Đây là cổng thanh toán mô phỏng dùng cho demo: sau khi bấm xác nhận, hệ thống sẽ chuyển hướng và đánh dấu đã thanh toán.
                </div>

                <div class="d-flex flex-wrap gap-2">
                    <button type="submit" class="btn btn-lg text-white fw-semibold px-4" style="background:linear-gradient(135deg,#0d6efd,#0dcaf0);border:none;border-radius:10px;">
                        <i class="fas fa-check me-1"></i> Xác nhận thanh toán
                    </button>
                    <a href="{{ url()->previous() }}" class="btn btn-lg btn-outline-secondary px-4 rounded-3">Quay lại</a>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .payment-amount-banner {
        background: linear-gradient(135deg, #0f3460 0%, #16213e 100%);
    }
    .user-payment-method input:checked + .user-payment-method-inner {
        border-color: #0dcaf0 !important;
        background: rgba(13, 202, 240, 0.1);
        box-shadow: 0 0 0 3px rgba(13, 202, 240, 0.22);
    }
    .user-payment-method .user-payment-method-inner {
        cursor: pointer;
        transition: all 0.2s ease;
        border-width: 2px !important;
    }
    .user-payment-method:hover .user-payment-method-inner {
        border-color: #0d6efd !important;
    }
</style>
@endsection
