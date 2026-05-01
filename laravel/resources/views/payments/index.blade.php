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




@endsection