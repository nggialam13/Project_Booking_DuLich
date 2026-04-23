<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết thanh toán</title>
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
            max-width:900px;
            margin:0 auto;
        }
        .card{
            background:#fff;
            border-radius:14px;
            box-shadow:0 8px 24px rgba(0,0,0,0.08);
            overflow:hidden;
        }
        .card-header{
            background:#6f42c1;
            color:#fff;
            padding:22px 28px;
        }
        .card-body{
            padding:28px;
        }
        .detail-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
        }
        .detail-item{
            background:#f8f9fa;
            border:1px solid #e9ecef;
            border-radius:12px;
            padding:18px;
        }
        .detail-item label{
            display:block;
            color:#666;
            margin-bottom:8px;
            font-size:14px;
        }
        .detail-item strong{
            font-size:18px;
            color:#222;
        }
        .full{
            grid-column:1/-1;
        }
        .badge{
            display:inline-block;
            padding:7px 14px;
            border-radius:999px;
            font-size:13px;
            font-weight:bold;
        }
        .paid{
            background:#d1e7dd;
            color:#0f5132;
        }
        .pending{
            background:#fff3cd;
            color:#856404;
        }
        .failed{
            background:#f8d7da;
            color:#842029;
        }
        .btn-group{
            margin-top:24px;
            display:flex;
            gap:12px;
            flex-wrap:wrap;
        }
        .btn{
            text-decoration:none;
            border:none;
            padding:12px 18px;
            border-radius:10px;
            font-weight:bold;
            cursor:pointer;
        }
        .btn-primary{
            background:#0d6efd;
            color:#fff;
        }
        .btn-secondary{
            background:#6c757d;
            color:#fff;
        }
        @media (max-width:768px){
            .detail-grid{
                grid-template-columns:1fr;
            }
            .full{
                grid-column:auto;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Chi tiết thanh toán #{{ $payment->id }}</h2>
        </div>

        <div class="card-body">
            <div class="detail-grid">
                <div class="detail-item">
                    <label>Mã payment</label>
                    <strong>#{{ $payment->id }}</strong>
                </div>

                <div class="detail-item">
                    <label>Mã booking</label>
                    <strong>#{{ $payment->booking_id }}</strong>
                </div>

                <div class="detail-item">
                    <label>Số tiền</label>
                    <strong style="color:#dc3545;">{{ number_format($payment->amount, 0, ',', '.') }} VNĐ</strong>
                </div>

                <div class="detail-item">
                    <label>Phương thức</label>
                    <strong>{{ strtoupper($payment->payment_method) }}</strong>
                </div>

                <div class="detail-item">
                    <label>Trạng thái</label>
                    @if($payment->status == 'paid')
                        <span class="badge paid">Đã thanh toán</span>
                    @elseif($payment->status == 'pending')
                        <span class="badge pending">Đang chờ</span>
                    @else
                        <span class="badge failed">Thất bại</span>
                    @endif
                </div>

                <div class="detail-item">
                    <label>Mã giao dịch</label>
                    <strong>
                        {{ strtoupper($payment->payment_method) }}-{{ date('Ymd', strtotime($payment->created_at)) }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
                    </strong>
                </div>

                <div class="detail-item full">
                    <label>Ngày tạo</label>
                    <strong>{{ $payment->created_at }}</strong>
                </div>
            </div>

            <div class="btn-group">
                <a href="{{ route('payment.index') }}" class="btn btn-primary">Quay lại lịch sử</a>
                <a href="{{ url()->previous() }}" class="btn btn-secondary">Trang trước</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>