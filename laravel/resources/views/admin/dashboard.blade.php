@extends('layouts.admin')

@section('content')

@section('content')
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Quản lý người dùng</h5>
                <p class="card-text">Xem, xóa tài khoản người dùng</p>
                <a href="{{ route('admin.users') }}" class="btn btn-light">Truy cập</a>
            </div>
        </div>
    </div>
@endsection