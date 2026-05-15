@php
    $paidPayment = $booking->payments->firstWhere('status', 'paid');
    $pendingPayment = $booking->payments->where('status', 'pending')->sortByDesc('id')->first();
@endphp

@if($booking->status === 'cancelled')
    <span class="text-muted small">Không thể thanh toán (đã hủy)</span>
@elseif($paidPayment)
    <a href="{{ route('payment.show', $paidPayment->id) }}" class="btn btn-pay-done">
        <i class="fas fa-check-circle me-1"></i> Đã thanh toán
    </a>
@elseif($pendingPayment)
    @if($pendingPayment->payment_method === 'momo')
        <a href="{{ route('payment.momo', $pendingPayment->id) }}" class="btn btn-pay">
            <i class="fas fa-credit-card me-1"></i> Tiếp tục thanh toán
        </a>
    @elseif($pendingPayment->payment_method === 'vnpay')
        <a href="{{ route('payment.vnpay', $pendingPayment->id) }}" class="btn btn-pay">
            <i class="fas fa-credit-card me-1"></i> Tiếp tục thanh toán
        </a>
    @elseif($pendingPayment->payment_method === 'cash')
        <a href="{{ route('payment.show', $pendingPayment->id) }}" class="btn btn-pay">
            <i class="fas fa-money-bill-wave me-1"></i> Chờ xác nhận tiền mặt
        </a>
    @else
        <a href="{{ route('payment.show', $pendingPayment->id) }}" class="btn btn-pay">
            <i class="fas fa-clock me-1"></i> Đang chờ xử lý
        </a>
    @endif
@else
    <a href="{{ route('payment.create', $booking->id) }}" class="btn btn-pay">
        <i class="fas fa-wallet me-1"></i> Thanh toán
    </a>
@endif
