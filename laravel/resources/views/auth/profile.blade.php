@extends('layouts.master')

@section('title', 'Hồ sơ của tôi')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-4">
            <!-- Card Avatar & Thông tin cơ bản -->
            <div class="card">
                <div class="card-header text-center">Ảnh đại diện</div>
                <div class="card-body text-center">
                    @if($user->avatar)
                        <img src="{{ Storage::url($user->avatar) }}" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 64px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif
                    <h5>{{ $user->name }}</h5>
                    <p class="text-muted">{{ $user->email }}</p>
                    <span class="badge bg-{{ $user->role == 'admin' ? 'danger' : 'primary' }}">{{ ucfirst($user->role) }}</span>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <!-- Form cập nhật thông tin -->
            <div class="card">
                <div class="card-header">Cập nhật thông tin cá nhân</div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('updateProfile') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="gender" class="form-label">Giới tính</label>
                                <select class="form-control" id="gender" name="gender">
                                    <option value="">Chọn...</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="dob" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', optional($user->dob)->format('Y-m-d')) }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="avatar" class="form-label">Ảnh đại diện</label>
                                <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                                <small class="text-muted">Định dạng: jpeg, png, jpg, gif. Tối đa 2MB</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="interests" class="form-label">Sở thích</label>
                                <textarea class="form-control" id="interests" name="interests" rows="2" placeholder="VD: Du lịch, chụp ảnh, ẩm thực...">{{ old('interests', $user->interests) }}</textarea>
                            </div>
                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">Giới thiệu sơ lược</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Đôi nét về bản thân...">{{ old('bio', $user->bio) }}</textarea>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </form>
                </div>
            </div>

            <!-- Link đổi mật khẩu (đã tách riêng) -->
            <div class="card mt-3">
                <div class="card-body">
                    <h5>Bảo mật tài khoản</h5>
                    <p>Để bảo vệ tài khoản, bạn nên thường xuyên đổi mật khẩu.</p>
                    <a href="{{ route('change-password.form') }}" class="btn btn-outline-warning">Đổi mật khẩu</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection