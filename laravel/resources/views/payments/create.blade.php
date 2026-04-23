<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán</title>
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
            background:linear-gradient(135deg,#0d6efd,#4dabf7);
            color:#fff;
            padding:22px 28px;
        }
        .card-header h2{
            font-size:28px;
            margin-bottom:6px;
        }
        .card-body{
            padding:28px;
        }
        .info-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
            margin-bottom:24px;
        }
        .info-box{
            background:#f8f9fa;
            border:1px solid #e9ecef;
            border-radius:12px;
            padding:18px;
        }
        .info-box label{
            display:block;
            color:#666;
            font-size:14px;
            margin-bottom:8px;
        }
        .info-box strong{
            font-size:20px;
            color:#222;
        }
        .amount{
            color:#dc3545;
            font-size:30px;
            font-weight:bold;
        }
        .section-title{
            font-size:20px;
            margin-bottom:16px;
            color:#222;
        }
        .method-list{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:18px;
            margin-bottom:24px;
        }
        .method-item{
            position:relative;
        }
        .method-item input{
            position:absolute;
            opacity:0;
        }
        .method-label{
            display:block;
            border:2px solid #dee2e6;
            border-radius:14px;
            padding:20px;
            cursor:pointer;
            background:#fff;
            transition:0.2s;
        }
        .method-label h4{
            margin-bottom:10px;
            font-size:20px;
            color:#222;
        }
        .method-label p{
            color:#666;
            font-size:14px;
            line-height:1.6;
        }
        .method-item input:checked + .method-label{
            border-color:#0d6efd;
            background:#eef5ff;
        }
        .btn-group{
            display:flex;
            gap:12px;
            flex-wrap:wrap;
            margin-top:10px;
        }
        .btn{
            border:none;
            padding:14px 22px;
            border-radius:10px;
            cursor:pointer;
            font-size:16px;
            font-weight:bold;
            text-decoration:none;
            display:inline-block;
        }
        .btn-primary{
            background:#0d6efd;
            color:#fff;
        }
        .btn-primary:hover{
            background:#0b5ed7;
        }
        .btn-secondary{
            background:#6c757d;
            color:#fff;
        }
        .btn-secondary:hover{
            background:#5c636a;
        }
        .note{
            margin-top:18px;
            padding:14px 16px;
            background:#fff3cd;
            border:1px solid #ffe69c;
            border-radius:10px;
            color:#856404;
        }
        @media (max-width:768px){
            .info-grid,
            .method-list{
                grid-template-columns:1fr;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <div class="card-header">
            <h2>Thanh toán booking</h2>
            <p>Vui lòng kiểm tra thông tin trước khi thanh toán</p>
        </div>

        <div class="card-body">
            <div class="info-grid">
                <div class="info-box">
                    <label>Mã booking</label>
                    <strong>#{{ $booking->id }}</strong>
                </div>

                <div class="info-box">
                    <label>Trạng thái booking</label>
                    <strong>{{ $booking->status ?? 'pending' }}</strong>
                </div>

                <div class="info-box" style="grid-column:1/-1;">
                    <label>Số tiền cần thanh toán</label>
                    <div class="amount">{{ number_format($amount, 0, ',', '.') }} VNĐ</div>
                </div>
            </div>

            <form action="{{ route('payment.store') }}" method="POST">
                @csrf

                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="amount" value="{{ $amount }}">

                <h3 class="section-title">Chọn phương thức thanh toán</h3>

                <div class="method-list">
                    <div class="method-item">
                        <input type="radio" name="method" id="momo" value="momo" checked>
                        <label for="momo" class="method-label">
                            <h4>MoMo</h4>
                            <p>Thanh toán nhanh bằng cổng giả lập MoMo. Phù hợp để test chức năng pending → paid.</p>
                        </label>
                    </div>

                    <div class="method-item">
                        <input type="radio" name="method" id="vnpay" value="vnpay">
                        <label for="vnpay" class="method-label">
                            <h4>VNPay</h4>
                            <p>Thanh toán nhanh bằng cổng giả lập VNPay. Dùng để kiểm tra luồng thanh toán và cập nhật booking.</p>
                        </label>
                    </div>
                </div>

                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Xác nhận thanh toán</button>
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </form>

            
        </div>
    </div>
</div>
</body>
</html>