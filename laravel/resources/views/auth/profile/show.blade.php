@extends('layouts.master')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Hồ sơ của tôi</h4>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/' . $user->avatar) }}" class="rounded-circle" width="150" height="150">
                        @else
                            <div class="bg-secondary rounded-circle d-inline-flex align-items-center justify-content-center" style="width:150px;height:150px;font-size:3rem;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                    </div>

                    <table class="table table-bordered">
                        <tr><th>Họ tên</th><td>{{ $user->name }}</td></tr>
                        <tr><th>Email</th><td>{{ $user->email }}</td></tr>
                        <tr><th>Số điện thoại</th><td>{{ $user->phone ?? '—' }}</td></tr>
                        <tr><th>Giới thiệu</th><td>{{ $user->bio ?? '—' }}</td></tr>
                        <tr><th>Giới tính</th><td>{{ $user->gender ?? '—' }}</td></tr>
                        <tr><th>Ngày sinh</th><td>{{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d/m/Y') : '—' }}</td></tr>
                    </table>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">Chỉnh sửa hồ sơ</a>
                        <a href="{{ route('change-password.form') }}" class="btn btn-warning">Đổi mật khẩu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection