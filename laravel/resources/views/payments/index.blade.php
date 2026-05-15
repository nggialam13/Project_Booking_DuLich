@extends('layouts.master')

@section('content')
<div class="payment-card">
    <div class="payment-header d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h2 class="mb-1"><i class="fas fa-receipt me-2"></i>Lịch sử thanh toán</h2>
            <p class="mb-0">Các giao dịch liên quan đến booking của bạn</p>
        </div>
        <form method="get" action="{{ route('payment.index') }}" class="d-flex align-items-center gap-2 flex-wrap">
            <label for="filter-user-payment-status" class="small mb-0 text-white-50">Trạng thái</label>
            <select name="status" id="filter-user-payment-status" class="form-select form-select-sm" style="min-width: 10rem;" onchange="this.form.submit()">
                <option value="" @selected(($statusFilter ?? null) === null)>Tất cả</option>
                <option value="pending" @selected(($statusFilter ?? null) === 'pending')>Đang chờ</option>
                <option value="paid" @selected(($statusFilter ?? null) === 'paid')>Đã thanh toán</option>
            </select>
            @if(($statusFilter ?? null) !== null)
                <a href="{{ route('payment.index') }}" class="btn btn-sm btn-light">Xóa lọc</a>
            @endif
        </form>
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
                            <td class="text-nowrap">
                                @if($payment->status === 'pending' && in_array($payment->payment_method, ['momo', 'vnpay']))
                                    <a href="{{ route('payment.'.$payment->payment_method, $payment->id) }}" class="btn-view me-1">Thanh toán</a>
                                @endif
                                <a href="{{ route('payment.show', $payment->id) }}" class="btn-view">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty">
                                Chưa có giao dịch thanh toán nào.
                                <div class="mt-2">
                                    <a href="{{ route('bookings.index') }}" class="btn-view">Xem booking của tôi</a>
                                </div>
                            </td>
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
