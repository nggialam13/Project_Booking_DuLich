@extends('layouts.master')

@section('content')
<div class="container py-4" style="max-width: 900px;">
    <div class="payment-card">
        <div class="payment-header" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);">
            <h2><i class="fas fa-file-invoice me-2"></i>Chi tiết thanh toán #{{ $payment->id }}</h2>
            <p class="mb-0">Mã booking: {{ $payment->booking?->booking_code ?? ('#'.$payment->booking_id) }}</p>
        </div>
        <div class="payment-body">
            @if($payment->payment_method === 'cash' && $payment->status === 'pending')
                <div class="alert alert-info border-0 mb-4" style="border-radius:12px;">
                    <h6 class="fw-bold mb-2"><i class="fas fa-money-bill-wave me-2"></i>Hướng dẫn thanh toán tiền mặt</h6>
                    <ul class="mb-0 ps-3 small">
                        <li>Mang mã booking <strong>{{ $payment->booking?->booking_code }}</strong> đến quầy giao dịch.</li>
                        <li>Thanh toán đúng số tiền: <strong>{{ number_format($payment->amount, 0, ',', '.') }} đ</strong>.</li>
                        <li>Sau khi thu tiền, quản trị viên sẽ xác nhận và booking chuyển sang trạng thái đã xác nhận.</li>
                    </ul>
                </div>
            @endif
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <small class="text-muted d-block mb-1">Số tiền</small>
                        <strong class="fs-4" style="color:#0d6efd;">{{ number_format($payment->amount, 0, ',', '.') }} đ</strong>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <small class="text-muted d-block mb-1">Phương thức</small>
                        @php $m = $payment->payment_method; @endphp
                        @if($m === 'cash')
                            <span class="badge rounded-pill bg-secondary fs-6">Tiền mặt</span>
                        @elseif($m === 'momo')
                            <span class="badge rounded-pill fs-6" style="background:#d82d8b;color:#fff;">MoMo</span>
                        @else
                            <span class="badge rounded-pill bg-primary fs-6">VNPay</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <small class="text-muted d-block mb-1">Trạng thái</small>
                        @if($payment->status === 'paid')
                            <span class="status paid">Đã thanh toán</span>
                        @else
                            <span class="status pending">Đang chờ</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 rounded-3 border bg-light h-100">
                        <small class="text-muted d-block mb-1">Mã giao dịch (hiển thị)</small>
                        <strong class="small text-break">
                            {{ strtoupper($payment->payment_method) }}-{{ $payment->created_at?->format('Ymd') }}-{{ str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT) }}
                        </strong>
                    </div>
                </div>
                @if($payment->booking?->tour)
                    <div class="col-12">
                        <div class="p-3 rounded-3 border">
                            <small class="text-muted d-block mb-1">Tour</small>
                            <strong>{{ $payment->booking->tour->title }}</strong>
                        </div>
                    </div>
                @endif
                <div class="col-12">
                    <div class="p-3 rounded-3 border bg-light">
                        <small class="text-muted d-block mb-1">Thời gian tạo</small>
                        <strong>{{ $payment->created_at?->format('d/m/Y H:i') }}</strong>
                    </div>
                </div>
            </div>

            <div class="d-flex flex-wrap gap-2">
                @if($payment->status === 'pending' && in_array($payment->payment_method, ['momo', 'vnpay']))
                    <a href="{{ route('payment.'.$payment->payment_method, $payment->id) }}" class="btn btn-lg text-white fw-semibold px-4" style="background:linear-gradient(135deg,#0d6efd,#0dcaf0);border:none;border-radius:10px;">
                        <i class="fas fa-credit-card me-1"></i> Tiếp tục thanh toán
                    </a>
                @endif
                <a href="{{ route('payment.index') }}" class="btn-view">← Lịch sử thanh toán</a>
                <a href="{{ route('bookings.index') }}" class="btn btn-outline-primary rounded-3 fw-semibold px-3">Booking của tôi</a>
            </div>
        </div>
    </div>
</div>
@endsection
