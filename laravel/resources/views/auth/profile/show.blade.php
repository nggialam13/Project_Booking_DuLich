@extends('layouts.master')

@section('title', 'Hồ sơ của tôi')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                    <!-- Header card với avatar và tên -->
                    <div class="bg-primary bg-gradient p-4 text-white text-center">
                        <div class="position-relative d-inline-block">
                            @if($user->avatar && Storage::disk('public')->exists($user->avatar))
                                <img src="{{ asset('storage/' . $user->avatar) }}"
                                    class="rounded-circle border border-white border-3 shadow-sm" width="120" height="120"
                                    style="object-fit: cover;">
                            @else
                                <div style="font-size: 80px;">🙂</div>
                            @endif
                        </div>
                        <h3 class="mt-3 mb-0">{{ $user->name }}</h3>
                        <p class="text-white-50 mb-0">
                            <i class="fas fa-envelope"></i> {{ $user->email }}
                        </p>
                    </div>

                    <!-- Nội dung chi tiết -->
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 text-primary"><i class="fas fa-phone fa-fw"></i></div>
                                    <div>
                                        <small class="text-muted">Số điện thoại</small>
                                        <p class="mb-0 fw-semibold">{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 text-primary"><i class="fas fa-venus-mars fa-fw"></i></div>
                                    <div>
                                        <small class="text-muted">Giới tính</small>
                                        <p class="mb-0 fw-semibold">
                                            @if($user->gender == 'male') Nam
                                            @elseif($user->gender == 'female') Nữ
                                            @elseif($user->gender == 'other') Khác
                                            @else Chưa cập nhật
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 text-primary"><i class="fas fa-calendar-alt fa-fw"></i></div>
                                    <div>
                                        <small class="text-muted">Ngày sinh</small>
                                        <p class="mb-0 fw-semibold">
                                            {{ $user->dob ? $user->dob->format('d/m/Y') : 'Chưa cập nhật' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <div class="me-3 text-primary"><i class="fas fa-user-tag fa-fw"></i></div>
                                    <div>
                                        <small class="text-muted">Vai trò</small>
                                        <p class="mb-0 fw-semibold">
                                            @if($user->role === 'admin')
                                                <span class="badge bg-danger">Quản trị viên</span>
                                            @else
                                                <span class="badge bg-info">Thành viên</span>
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Giới thiệu -->
                        <div class="mt-3">
                            <h6 class="border-start border-primary border-4 ps-3 mb-2">Giới thiệu</h6>
                            <p class="text-secondary">{{ $user->bio ?? 'Chưa có giới thiệu.' }}</p>
                        </div>

                        <!-- Sở thích -->
                        <div class="mt-3">
                            <h6 class="border-start border-primary border-4 ps-3 mb-2">Sở thích</h6>
                            <div>
                                @if($user->interests)
                                    @foreach(explode(',', $user->interests) as $interest)
                                        <span class="badge bg-light text-dark me-1 mb-1 p-2">{{ trim($interest) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">Chưa cập nhật</span>
                                @endif
                            </div>
                        </div>

                        <!-- Nút hành động -->
                        <div class="mt-4 d-flex gap-2">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                <i class="fas fa-pen"></i> Chỉnh sửa hồ sơ
                            </a>
                            <a href="{{ route('change-password.form') }}" class="btn btn-outline-warning">
                                <i class="fas fa-key"></i> Đổi mật khẩu
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection