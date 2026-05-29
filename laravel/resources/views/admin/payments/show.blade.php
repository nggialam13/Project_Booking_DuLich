@extends('layouts.admin')

@section('title', 'Chi tiết thanh toán')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-receipt me-2"></i>Thanh toán #{{ $payment->id }}</h2>
            <p class="text-muted mb-0">Xem thông tin giao dịch và cập nhật trạng thái</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Danh sách
            </a>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-8">
            <div class="card card-admin mb-3">
                <div class="card-admin-header">
                    <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin giao dịch</h5>
                </div>
                <div class="card-admin-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Mã thanh toán</small>
                            <strong class="fs-5">#{{ $payment->id }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Mã giao dịch (hiển thị)</small>
                            <strong class="text-break">
                                {{ strtoupper($payment->payment_method) }}-{{ $payment->created_at?->format('Ymd') }}-{{ str_pad((string) $payment->id, 4, '0', STR_PAD_LEFT) }}
                            </strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Booking</small>
                            @if($payment->booking)
                                <span class="fw-semibold">{{ $payment->booking->booking_code ?? ('#'.$payment->booking_id) }}</span>
                                @if(Route::has('admin.bookings.index'))
                                    <a href="{{ route('admin.bookings.index') }}" class="small d-block mt-1">Mở danh sách booking</a>
                                @endif
                                @if($payment->booking->tour)
                                    <div class="small text-muted mt-1">{{ $payment->booking->tour->title }}</div>
                                @endif
                            @else
                                <span class="text-muted">#{{ $payment->booking_id }}</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Khách hàng</small>
                            <strong>{{ $payment->booking?->user?->name ?? '—' }}</strong>
                            @if($payment->booking?->user?->email)
                                <div class="small text-muted">{{ $payment->booking->user->email }}</div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Phương thức</small>
                            @php $m = $payment->payment_method; @endphp
                            @if($m === 'cash')
                                <span class="badge rounded-pill bg-secondary">Tiền mặt</span>
                            @elseif($m === 'momo')
                                <span class="badge rounded-pill" style="background:#d82d8b;color:#fff;">MoMo</span>
                            @else
                                <span class="badge rounded-pill bg-primary">VNPay</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Trạng thái hiện tại</small>
                            @if($payment->status === 'paid')
                                <span class="badge badge-status-success">Đã thanh toán</span>
                            @else
                                <span class="badge badge-status-warning">Đang chờ</span>
                            @endif
                        </div>
                        <div class="col-12">
                            <small class="text-muted d-block mb-1">Số tiền</small>
                            <div class="rounded-3 p-4 text-center" style="background: linear-gradient(135deg, #0f3460 0%, #16213e 100%); color: #fff;">
                                <span class="display-6 fw-bold">{{ number_format($payment->amount, 0, ',', '.') }}</span>
                                <span class="fs-4 ms-1">đ</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Ngày tạo</small>
                            <strong>{{ $payment->created_at?->format('d/m/Y H:i') }}</strong>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted d-block mb-1">Cập nhật lần cuối</small>
                            <strong>{{ $payment->updated_at?->format('d/m/Y H:i') }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card card-admin mb-3">
                <div class="card-admin-header">
                    <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Cập nhật trạng thái</h5>
                </div>
                <div class="card-admin-body">
                    <p class="small text-muted mb-3">
                        Khi chuyển sang <strong>Đã thanh toán</strong>, hệ thống sẽ đặt booking thành <strong>confirmed</strong> (trừ booking đã hủy).
                        Khi chuyển về <strong>Đang chờ</strong>, booking sẽ về <strong>pending</strong> (trừ booking đã hủy).
                    </p>
                    <form action="{{ route('admin.payments.update-status', $payment) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <label for="status" class="form-label fw-semibold">Trạng thái thanh toán</label>
                        <select name="status" id="status" class="form-select form-select-lg mb-3" required>
                            <option value="pending" @selected(old('status', $payment->status) === 'pending')>Đang chờ</option>
                            <option value="paid" @selected(old('status', $payment->status) === 'paid')>Đã thanh toán</option>
                        </select>
                        @error('status')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="btn btn-admin btn-admin-primary w-100">
                            <i class="fas fa-save me-1"></i> Lưu trạng thái
                        </button>
                    </form>
                </div>
            </div>

            @if($payment->booking)
                <div class="card card-admin">
                    <div class="card-admin-header">
                        <h5 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Booking</h5>
                    </div>
                    <div class="card-admin-body">
                        <p class="mb-1"><span class="text-muted small">Trạng thái booking</span></p>
                        @php $bs = $payment->booking->status; @endphp
                        @if($bs === 'confirmed')
                            <span class="badge bg-success">Confirmed</span>
                        @elseif($bs === 'cancelled')
                            <span class="badge bg-danger">Cancelled</span>
                        @else
                            <span class="badge bg-warning text-dark">Pending</span>
                        @endif
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
