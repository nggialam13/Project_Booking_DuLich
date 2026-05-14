@extends('layouts.admin')

@section('title', 'Quản lý thanh toán')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-credit-card me-2"></i>Quản lý thanh toán</h2>
            <p class="text-muted mb-0">Theo dõi giao dịch, ghi nhận thanh toán và cập nhật trạng thái</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payments.create') }}" class="btn btn-admin btn-admin-primary px-4">
                <i class="fas fa-plus me-1"></i> Tạo thanh toán
            </a>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card card-admin mb-0 h-100">
                <div class="card-admin-body">
                    <small class="text-muted d-block mb-1"><i class="fas fa-list-ul me-1"></i>Tổng giao dịch</small>
                    <strong class="fs-3 text-dark">{{ number_format($stats['total']) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-admin mb-0 h-100">
                <div class="card-admin-body">
                    <small class="text-muted d-block mb-1"><i class="fas fa-check-circle me-1"></i>Đã thanh toán</small>
                    <strong class="fs-3 text-success">{{ number_format($stats['paid_count']) }}</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-admin mb-0 h-100">
                <div class="card-admin-body">
                    <small class="text-muted d-block mb-1"><i class="fas fa-coins me-1"></i>Doanh thu (đã paid)</small>
                    <strong class="fs-4" style="color:#0d6efd;">
                        {{ number_format($stats['revenue'], 0, ',', '.') }} đ
                    </strong>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-admin">
        <div class="card-admin-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="mb-0"><i class="fas fa-list me-2"></i>Danh sách thanh toán</h5>
        </div>
        <div class="card-admin-body p-0">
            <div class="table-responsive">
                <table class="table table-admin mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Booking</th>
                            <th>Khách</th>
                            <th class="text-end">Số tiền</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td class="fw-semibold">#{{ $payment->id }}</td>
                                <td>
                                    @if($payment->booking)
                                        <span class="fw-semibold d-block">{{ $payment->booking->booking_code ?? ('#'.$payment->booking_id) }}</span>
                                        @if($payment->booking->tour)
                                            <small class="text-muted">{{ str($payment->booking->tour->title ?? '')->limit(42) }}</small>
                                        @endif
                                    @else
                                        <span class="text-muted">#{{ $payment->booking_id }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="d-block">{{ $payment->booking?->user?->name ?? '—' }}</span>
                                    <small class="text-muted">{{ $payment->booking?->user?->email ?? '' }}</small>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold" style="color:#c0392b;">{{ number_format($payment->amount, 0, ',', '.') }}</span>
                                    <span class="text-muted small">đ</span>
                                </td>
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
                                        <span class="badge badge-status-success">Đã thanh toán</span>
                                    @else
                                        <span class="badge badge-status-warning">Đang chờ</span>
                                    @endif
                                </td>
                                <td><small>{{ $payment->created_at?->format('d/m/Y H:i') }}</small></td>
                                <td class="text-center">
                                    <a href="{{ route('admin.payments.show', $payment) }}" class="btn btn-sm btn-admin btn-admin-primary">
                                        <i class="fas fa-eye me-1"></i> Chi tiết
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    <i class="fas fa-inbox fa-2x d-block mb-2 opacity-50"></i>
                                    Chưa có dữ liệu thanh toán.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($payments->hasPages())
            <div class="card-admin-body border-top pt-3">
                {{ $payments->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
