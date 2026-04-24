@extends('layouts.app')

@section('content')
<div class="container my-5">
    <div class="text-center mb-5">
        <h1 class="display-4 fw-bold text-primary">Chào mừng đến với Booking Tour</h1>
        <p class="lead">Khám phá và đặt tour du lịch tuyệt vời với chúng tôi</p>
        <a href="/tours" class="btn btn-primary btn-lg">Xem Tour</a>
    </div>

    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tour Phượt</h5>
                    <p class="card-text">Khám phá những cung đường mạo hiểm.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tour Biển</h5>
                    <p class="card-text">Thư giãn bên bờ biển tuyệt đẹp.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <h5 class="card-title">Tour Núi</h5>
                    <p class="card-text">Chinh phục đỉnh núi và ngắm cảnh.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection