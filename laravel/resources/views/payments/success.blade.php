<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thanh toán thành công</title>
</head>
<body>
    <h2>Thanh toán thành công</h2>
    <p>Mã payment: {{ $payment->id }}</p>
    <p>Booking: {{ $payment->booking_id }}</p>
    <p>Số tiền: {{ number_format($payment->amount, 0, ',', '.') }} VNĐ</p>
    <p>Phương thức: {{ strtoupper($payment->payment_method) }}</p>
    <p>Mã giao dịch:
        {{ strtoupper($payment->payment_method) }}-{{ date('Ymd', strtotime($payment->created_at)) }}-{{ str_pad($payment->id, 4, '0', STR_PAD_LEFT) }}
    </p>

    <a href="{{ route('payment.index') }}">Xem lịch sử thanh toán</a>
</body>
</html>