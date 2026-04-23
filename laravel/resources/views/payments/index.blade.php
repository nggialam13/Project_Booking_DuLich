<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Lịch sử thanh toán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:Arial, sans-serif;
        }
        body{
            background:#f4f7fb;
            padding:30px;
        }
        .container{
            max-width:1100px;
            margin:0 auto;
        }
        .card{
            background:#fff;
            border-radius:14px;
            box-shadow:0 8px 24px rgba(0,0,0,0.08);
            overflow:hidden;
        }
        .card-header{
            background:#198754;
            color:#fff;
            padding:22px 28px;
        }
        .card-body{
            padding:24px;
        }
        table{
            width:100%;
            border-collapse:collapse;
        }
        th, td{
            padding:14px 12px;
            border-bottom:1px solid #eee;
            text-align:left;
        }
        th{
            background:#f8f9fa;
        }
        .badge{
            display:inline-block;
            padding:6px 12px;
            border-radius:999px;
            font-size:13px;
            font-weight:bold;
        }
        .badge-paid{
            background:#d1e7dd;
            color:#0f5132;
        }
        .badge-pending{
            background:#fff3cd;
            color:#856404;
        }
        .badge-failed{
            background:#f8d7da;
            color:#842029;
        }
        .btn-detail{
            text-decoration:none;
            background:#0d6efd;
            color:#fff;
            padding:8px 14px;
            border-radius:8px;
        }
        .empty{
            text-align:center;
            padding:30px;
            color:#777;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Lịch sử thanh toán</h2>
        </div>

        <div class="card-body">
            <table>
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
                            <td>{{ $payment->id }}</td>
                            <td>#{{ $payment->booking_id }}</td>
                            <td>{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</td>

                            <!-- SỬA Ở ĐÂY -->
                            <td>{{ strtoupper($payment->payment_method) }}</td>

                            <td>
                                @if($payment->status == 'paid')
                                    <span class="badge badge-paid">Đã thanh toán</span>
                                @elseif($payment->status == 'pending')
                                    <span class="badge badge-pending">Đang chờ</span>
                                @else
                                    <span class="badge badge-failed">Thất bại</span>
                                @endif
                            </td>

                            <!-- SỬA Ở ĐÂY -->
                            <td>
                                {{ strtoupper($payment->payment_method) }}-{{ date('Ymd', strtotime($payment->created_at)) }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                            </td>

                            <td>{{ $payment->created_at }}</td>

                            <td>
                                <a class="btn-detail" href="{{ route('payment.show', $payment->id) }}">Xem</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="empty">Chưa có lịch sử thanh toán nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>