@extends('layouts.master')

@section('content')

<div class="container py-5">

    <div class="payment-card">

        <!-- Header -->
        <div class="payment-header">
            <h2>💳 Lịch Sử Thanh Toán</h2>
            <p>Theo dõi toàn bộ giao dịch booking tour của bạn</p>
        </div>

        <!-- Body -->
        <div class="payment-body">

            <div class="table-responsive">

                <table class="table payment-table align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Booking</th>
                            <th>Số tiền</th>
                            <th>Phương thức</th>
                            <th>Trạng thái</th>
                            <th>Mã giao dịch</th>
                            <th>Ngày tạo</th>
                            <th>Chi tiết</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($payments as $payment)

                        <tr>

                            <td>#{{ $payment->id }}</td>

                            <td>
                                <span class="booking-code">
                                    #{{ $payment->booking_id }}
                                </span>
                            </td>

                            <td class="price">
                                {{ number_format($payment->amount, 0, ',', '.') }} đ
                            </td>

                            <td>
                                <span class="method">
                                    {{ strtoupper($payment->payment_method) }}
                                </span>
                            </td>

                            <td>
                                @if($payment->status == 'paid')
                                    <span class="status paid">Đã thanh toán</span>

                                @elseif($payment->status == 'pending')
                                    <span class="status pending">Đang chờ</span>

                                @else
                                    <span class="status failed">Thất bại</span>
                                @endif
                            </td>

                            <td class="code">
                                {{ strtoupper($payment->payment_method) }}-{{ date('Ymd', strtotime($payment->created_at)) }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td>
                                {{ date('d/m/Y H:i', strtotime($payment->created_at)) }}
                            </td>

                            <td>
                                <a href="{{ route('payment.show', $payment->id) }}" class="btn-view">
                                    Xem
                                </a>
                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="8" class="empty">
                                📭 Chưa có lịch sử thanh toán nào.
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>


<style>
.payment-card{
    background:#fff;
    border-radius:22px;
    overflow:hidden;
    box-shadow:0 18px 45px rgba(0,0,0,.08);
    animation:fadeUp .6s ease;
}

.payment-header{
    background:linear-gradient(135deg,#16a34a,#0ea5e9);
    color:#fff;
    padding:32px;
}

.payment-header h2{
    font-size:34px;
    font-weight:800;
    margin-bottom:8px;
}

.payment-header p{
    margin:0;
    opacity:.92;
    font-size:15px;
}

.payment-body{
    padding:30px;
}

.payment-table{
    margin:0;
}

.payment-table thead th{
    background:#f8fafc;
    border:none;
    font-size:14px;
    font-weight:700;
    color:#334155;
    padding:16px;
}

.payment-table tbody td{
    padding:18px 16px;
    vertical-align:middle;
    border-bottom:1px solid #eef2f7;
}

.payment-table tbody tr{
    transition:.3s;
}

.payment-table tbody tr:hover{
    background:#f8fbff;
    transform:scale(1.003);
}

.price{
    font-weight:800;
    color:#0d6efd;
}

.booking-code{
    background:#eff6ff;
    color:#1d4ed8;
    padding:6px 12px;
    border-radius:50px;
    font-weight:700;
    font-size:13px;
}

.method{
    background:#f1f5f9;
    padding:6px 12px;
    border-radius:50px;
    font-weight:700;
    font-size:13px;
}

.status{
    padding:7px 14px;
    border-radius:50px;
    font-size:13px;
    font-weight:700;
}

.status.paid{
    background:#dcfce7;
    color:#15803d;
}

.status.pending{
    background:#fef3c7;
    color:#b45309;
}

.status.failed{
    background:#fee2e2;
    color:#dc2626;
}

.code{
    font-size:13px;
    color:#64748b;
    font-weight:600;
}

.btn-view{
    background:linear-gradient(135deg,#0d6efd,#0ea5e9);
    color:#fff;
    padding:9px 16px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
    transition:.3s;
}

.btn-view:hover{
    color:#fff;
    transform:translateY(-2px);
    box-shadow:0 10px 20px rgba(14,165,233,.25);
}

.empty{
    text-align:center;
    padding:45px !important;
    color:#64748b;
    font-weight:600;
}

@keyframes fadeUp{
    from{
        opacity:0;
        transform:translateY(20px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

@media(max-width:991px){

    .payment-header h2{
        font-size:26px;
    }

    .payment-body{
        padding:15px;
    }

    .payment-table{
        min-width:1000px;
    }
}
</style>

@endsection