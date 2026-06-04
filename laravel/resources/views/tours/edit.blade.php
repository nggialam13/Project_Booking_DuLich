@extends('layouts.admin')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h2><i class="fas fa-pen-to-square"></i> Sửa Tour</h2>
    </div>
    <div class="col-md-4 text-end">
        <a href="{{ route('tours.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

@if ($errors->any())
<div class="alert alert-admin alert-danger alert-dismissible fade show" role="alert">
    <i class="fas fa-exclamation-circle"></i> <strong>Vui lòng kiểm tra lại:</strong>
    <ul class="mb-0 mt-2">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

<div class="card-admin">
    <div class="card-admin-header">
        <h5><i class="fas fa-file-alt"></i> Thông tin Tour</h5>
    </div>
    <div class="card-admin-body">
        <form action="{{ route('tours.update', $tour->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Tiêu đề -->
            <div class="mb-4">
                <label for="title" class="form-label fw-600">Tiêu đề <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('title') is-invalid @enderror" 
                      id="title" name="title" value="{{ old('title', $tour->title) }}" maxlength="150"
                      placeholder="Nhập tiêu đề tour" required>
                @error('title')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Mô tả -->
            <div class="mb-4">
                <label for="description" class="form-label fw-600">Mô tả <span class="text-danger">*</span></label>
                <textarea class="form-control @error('description') is-invalid @enderror" 
                          id="description" name="description" rows="5" maxlength="150"
                          placeholder="Nhập mô tả chi tiết về tour" required>{{ old('description', $tour->description) }}</textarea>
                @error('description')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Địa điểm -->
            <div class="mb-4">
                <label for="location" class="form-label fw-600">Địa điểm <span class="text-danger">*</span></label>
                  <input type="text" class="form-control @error('location') is-invalid @enderror" 
                      id="location" name="location" value="{{ old('location', $tour->location) }}" maxlength="150"
                      placeholder="Nhập địa điểm tour" required>
                @error('location')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Giá & Thời gian -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="price" class="form-label fw-600">Giá (VND) <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('price') is-invalid @enderror" 
                           id="price" name="price" value="{{ old('price', $tour->price) }}" 
                           placeholder="0" step="1" required>
                    @error('price')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="duration" class="form-label fw-600">Thời gian (ngày)</label>
                    <input type="number" class="form-control" 
                           id="duration" name="duration" value="{{ old('duration', $tour->duration) }}" 
                           placeholder="0" readonly>
                    <small class="text-muted d-block mt-2">Được tính tự động từ ngày bắt đầu & kết thúc</small>
                </div>
            </div>

            <!-- Ngày bắt đầu & Kết thúc -->
            <div class="row">
                <div class="col-md-6 mb-4">
                    <label for="start_date" class="form-label fw-600">Ngày bắt đầu <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                           id="start_date" name="start_date" value="{{ old('start_date', $tour->start_date) }}" required>
                    @error('start_date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6 mb-4">
                    <label for="end_date" class="form-label fw-600">Ngày kết thúc <span class="text-danger">*</span></label>
                    <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                           id="end_date" name="end_date" value="{{ old('end_date', $tour->end_date) }}" required>
                    @error('end_date')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Số lượng chỗ -->
            <div class="mb-4">
                <label for="slots" class="form-label fw-600">Số lượng chỗ <span class="text-danger">*</span></label>
                <input type="number" class="form-control @error('slots') is-invalid @enderror" 
                       id="slots" name="slots" value="{{ old('slots', $tour->slots) }}" 
                       placeholder="0" required>
                @error('slots')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Hình ảnh -->
            <div class="mb-4">
                <label for="image" class="form-label fw-600">Hình ảnh</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" 
                       id="image" name="image" accept="image/*">
                <small class="text-muted d-block mt-2">Định dạng: PNG, JPG | Kích thước tối đa: 2MB</small>
                @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Buttons -->
            <div class="mt-5 pt-4 border-top">
                <button type="submit" class="btn btn-admin-primary btn-lg">
                    <i class="fas fa-save"></i> Lưu Thay Đổi
                </button>
                <a href="{{ route('tours.index') }}" class="btn btn-secondary btn-lg ms-2">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>

@endsection