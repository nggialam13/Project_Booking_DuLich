@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Admin - Danh sách thanh toán</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        *{margin:0;padding:0;box-sizing:border-box;font-family:Arial,sans-serif;}
        body{background:#eef2f7;padding:30px;}
        .container{max-width:1200px;margin:0 auto;}
        .header-box{background:linear-gradient(135deg,#212529,#495057);color:#fff;padding:24px 28px;border-radius:14px;margin-bottom:22px;box-shadow:0 8px 24px rgba(0,0,0,0.08);}
        .header-box h2{margin-bottom:8px;}
        .stats{display:grid;grid-template-columns:repeat(3,1fr);gap:18px;margin-bottom:22px;}
        .stat-card{background:#fff;border-radius:14px;padding:20px;box-shadow:0 8px 24px rgba(0,0,0,0.06);}
        .stat-card label{display:block;color:#777;margin-bottom:8px;}
        .stat-card strong{font-size:26px;color:#222;}
        .table-wrap{background:#fff;border-radius:14px;overflow:hidden;box-shadow:0 8px 24px rgba(0,0,0,0.06);}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:14px 12px;border-bottom:1px solid #eee;text-align:left;}
        th{background:#f8f9fa;}
        tr:hover{background:#fafafa;}
        .badge{display:inline-block;padding:6px 12px;border-radius:999px;font-size:13px;font-weight:bold;}
        .paid{background:#d1e7dd;color:#0f5132;}
        .pending{background:#fff3cd;color:#856404;}
        .failed{background:#f8d7da;color:#842029;}
        .btn{display:inline-block;text-decoration:none;background:#0d6efd;color:#fff;padding:8px 14px;border-radius:8px;font-size:14px;}
        .empty{padding:25px;text-align:center;color:#777;}
        @media (max-width:900px){.stats{grid-template-columns:1fr;}.table-wrap{overflow:auto;}}
    </style>
</head>
<body>
<div class="container">
    <div class="header-box">
        <h2>Quản lý thanh toán</h2>
        <p>Trang dành cho admin theo dõi giao dịch và doanh thu</p>
    </div>

    <div class="stats">
        <div class="stat-card">
            <label>Tổng giao dịch</label>
            <strong>{{ $payments->count() }}</strong>
        </div>

        <div class="stat-card">
            <label>Đã thanh toán</label>
            <strong>{{ $payments->where('status', 'paid')->count() }}</strong>
        </div>

        <div class="stat-card">
            <label>Tổng doanh thu</label>
            <strong>{{ number_format($payments->where('status', 'paid')->sum('amount'), 0, ',', '.') }} đ</strong>
        </div>
    </div>

    <div class="table-wrap">
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
                        <td>{{ strtoupper($payment->payment_method) }}</td>
                        <td>
                            @if($payment->status == 'paid')
                                <span class="badge paid">Đã thanh toán</span>
                            @elseif($payment->status == 'pending')
                                <span class="badge pending">Đang chờ</span>
                            @else
                                <span class="badge failed">Thất bại</span>
                            @endif
                        </td>
                        <td>{{ strtoupper($payment->payment_method) }}-{{ date('Ymd', strtotime($payment->created_at)) }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>{{ $payment->created_at }}</td>
                        <td>
                            <a href="{{ route('payment.show', $payment->id) }}" class="btn">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="empty">Chưa có dữ liệu thanh toán.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
@endsection