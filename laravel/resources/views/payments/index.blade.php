@extends('layouts.master')

@section('content')
<div class="container py-4">
    <div class="payment-card">
        <div class="payment-header">
            <h2><i class="fas fa-receipt me-2"></i>Lịch sử thanh toán</h2>
            <p class="mb-0">Các giao dịch liên quan đến booking của bạn</p>
        </div>
        <div class="payment-body">
            <div class="table-responsive">
                <table class="table payment-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Booking</th>
                            <th>Tour</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Ngày</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="fw-bold">#{{ $payment->id }}</td>
                                <td>
                                    <span class="booking-code">{{ $payment->booking?->booking_code ?? ('#'.$payment->booking_id) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ str($payment->booking?->tour?->title ?? '—')->limit(36) }}</small>
                                </td>
                                <td class="price">{{ number_format($payment->amount, 0, ',', '.') }} đ</td>
                                <td>
                                    @php $m = $payment->payment_method; @endphp
                                    @if($m === 'cash')
                                        <span class="badge rounded-pill bg-secondary">Tiền mặt</span>
                                    @elseif($m === 'momo')
                                        <span class="badge rounded-pill" style="background:#d82d8b;color:#fff;">MoMo</span>
                                    @else
                                        <span class="badge rounded-pill bg-primary">VNPay</span>
                                    @endif
                                </td>
                                <td>
                                    @if($payment->status === 'paid')
                                        <span class="status paid">Đã thanh toán</span>
                                    @else
                                        <span class="status pending">Đang chờ</span>
                                    @endif
                                </td>
                                <td>
                                    <small>{{ $payment->created_at?->format('d/m/Y H:i') }}</small>
                                </td>
                                <td>
                                    <a href="{{ route('payment.show', $payment->id) }}" class="btn-view">Chi tiết</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="empty">Chưa có giao dịch thanh toán nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        @if($payments->hasPages())
            <div class="pt-3 border-top mt-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
