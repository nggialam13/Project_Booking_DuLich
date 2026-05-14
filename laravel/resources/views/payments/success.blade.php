@extends('layouts.master')

@section('content')
<div class="container py-5" style="max-width: 640px;">
    <div class="payment-card text-center">
        <div class="payment-header py-5" style="background: linear-gradient(135deg, #198754, #20c997);">
            <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex align-items-center justify-content-center mb-3" style="width:72px;height:72px;">
                <i class="fas fa-check fa-2x text-white"></i>
            </div>
            <h2 class="mb-2">Thanh toán thành công</h2>
            <p class="mb-0 opacity-90">Giao dịch đã được ghi nhận</p>
        </div>
        <div class="payment-body text-start">
            <ul class="list-unstyled mb-4">
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Mã thanh toán</span>
                    <strong>#{{ $payment->id }}</strong>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Booking</span>
                    <strong>{{ $payment->booking?->booking_code ?? ('#'.$payment->booking_id) }}</strong>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Số tiền</span>
                    <strong class="text-primary">{{ number_format($payment->amount, 0, ',', '.') }} đ</strong>
                </li>
                <li class="d-flex justify-content-between py-2 border-bottom">
                    <span class="text-muted">Phương thức</span>
                    <strong>{{ strtoupper($payment->payment_method) }}</strong>
                </li>
                <li class="d-flex justify-content-between py-2">
                    <span class="text-muted">Mã giao dịch</span>
                    <strong class="small text-end">
                        {{ strtoupper($payment->payment_method) }}-{{ $payment->created_at?->format('Ymd') }}-{{ str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT) }}
                    </strong>
                </li>
            </ul>
            <div class="d-grid gap-2">
                <a href="{{ route('payment.index') }}" class="btn btn-lg text-white fw-semibold py-3 rounded-3" style="background:linear-gradient(135deg,#0d6efd,#0dcaf0);border:none;">
                    Xem lịch sử thanh toán
                </a>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-secondary btn-lg rounded-3">Về danh sách booking</a>
            </div>
        </div>
    </div>
</div>
@endsection
