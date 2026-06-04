@extends('layouts.master')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h4>Chỉnh sửa hồ sơ</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <!-- trường hợp đồng thời sửa dữ liệu -->
                            <input type="hidden" name="original_updated_at" value="{{ $user->updated_at }}">
                            <!-- kiểm tra image còn k -->
                            <div class="mb-3">
                                <label>Ảnh đại diện</label>
                                <input type="file" name="avatar" class="form-control" accept="image/*">
                                @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                    <img src="{{ asset('storage/' . $user->avatar) }}">
                                @else
                                    <div class="b-3">
                                        <span style="font-size: 60px;">🙂</span>
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label>Họ tên</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}" required>
                            </div>

                            <div class="mb-3">
                                <label>Số điện thoại</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>

                            <div class="mb-3">
                                <label>Giới thiệu sơ lược</label>
                                <textarea name="bio" class="form-control" rows="3">{{ old('bio', $user->bio) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label>Giới tính</label>
                                <select name="gender" class="form-select">
                                    <option value="">Chọn</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Nam
                                    </option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Nữ
                                    </option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Khác
                                    </option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label>Ngày sinh</label>
                                <input type="date" name="dob" class="form-control" value="{{ old('dob', $user->dob) }}">
                            </div>

                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                            <a href="{{ route('profile.show') }}" class="btn btn-secondary">Hủy</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection