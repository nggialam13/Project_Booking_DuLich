@extends('layouts.admin')

@section('title', 'Sửa người dùng')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h4>Sửa thông tin người dùng: {{ $user->name }}</h4>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                        @csrf
                        @method('PUT')
                        <!-- Thêm trường ẩn để kiểm tra dữ liệu đã bị thay đổi hay chưa -->
                        <input type="hidden" name="original_updated_at" value="{{ $user->updated_at }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">Họ tên</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                        </div>

                        <div class="mb-3">
                            <label for="role" class="form-label">Vai trò</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>Người dùng thường</option>
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Quản trị viên</option>
                            </select>
                            @if($user->id == Auth::id())
                                <small class="text-muted">Bạn đang sửa chính tài khoản của mình. Lưu ý: không thể tự thay đổi vai trò.</small>
                            @endif
                        </div>

                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">Hủy</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection