@extends('layouts.master')

@section('content')
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Các Tour Du Lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-light">

    <div class="container mb-5">
        <!-- Search Section -->
        @if($tours->count() > 0)
        <div class="card mb-4">
            <div class="card-body">
                <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Tìm Tour</h5>
                <form method="GET" class="d-flex gap-2">
                    <input type="text" name="search" class="form-control" placeholder="Tìm theo tên tour hoặc địa điểm..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>
        </div>

        <!-- Tour Grid -->
        <div class="row g-4 mb-5">
            @foreach($tours as $tour)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm">
                    <!-- Image -->
                    <div style="position: relative; height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); overflow: hidden;">
                        @if($tour->image)
                        <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}"
                            class="card-img-top" style="height: 100%; object-fit: cover;">
                        @else
                        <div class="d-flex align-items-center justify-content-center h-100 text-white">
                            <i class="fas fa-image" style="font-size: 60px;"></i>
                        </div>
                        @endif
                        <span class="badge bg-success position-absolute top-0 end-0 m-2">Còn Chỗ</span>
                    </div>

                    <!-- Content -->
                    <div class="card-body d-flex flex-column">
                        <p class="text-primary mb-2">
                            <i class="fas fa-map-pin"></i> {{ $tour->location }}
                        </p>

                        <h5 class="card-title mb-2">{{ $tour->title }}</h5>

                        <p class="card-text text-muted small mb-3" style="overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                            {{ $tour->description }}
                        </p>

                        <!-- Info Grid -->
                        <div class="row mb-3 pb-3 border-bottom">
                            <div class="col-6">
                                <small class="text-muted d-block">Giá Vé</small>
                                <strong class="text-primary">{{ number_format($tour->price) }} ₫</strong>
                            </div>
                            <div class="col-6">
                                <small class="text-muted d-block">Thời Gian</small>
                                <strong>{{ $tour->duration }} ngày</strong>
                            </div>
                        </div>

                        <!-- Dates -->
                        <p class="small text-muted mb-3">
                            <i class="fas fa-calendar-alt"></i>
                            {{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }} -
                            {{ \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') }}
                        </p>

                        <!-- Slots -->
                        <div class="d-flex justify-content-between align-items-center mb-3 p-2 bg-light rounded">
                            <span class="small"><i class="fas fa-users"></i> Chỗ còn trống</span>
                            @if($tour->available_slots === 0)
                            <span class="badge bg-danger">Hết Chỗ</span>
                            @elseif(($tour->available_slots / $tour->slots) * 100 <= 20)
                                <span class="badge bg-warning">{{ $tour->available_slots }}/{{ $tour->slots }}</span>
                                @else
                                <span class="badge bg-success">{{ $tour->available_slots }}/{{ $tour->slots }}</span>
                                @endif
                        </div>

                        <!-- Buttons -->
                        <div class="d-flex gap-2 mt-auto">
                            <button class="btn btn-outline-secondary btn-sm flex-grow-1" data-bs-toggle="modal"
                                data-bs-target="#tourModal{{ $tour->id }}">
                                <i class="fas fa-info-circle"></i> Chi Tiết
                            </button>
                            @if($tour->available_slots > 0)
                            <a href="{{ route('bookings.create', $tour->id) }}" class="btn btn-primary btn-sm flex-grow-1">
                                <i class="fas fa-ticket"></i> Đặt Ngay
                            </a>
                            @else
                            <button class="btn btn-secondary btn-sm flex-grow-1" disabled>
                                <i class="fas fa-ban"></i> Hết Chỗ
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Chi tiết Modal -->
            <div class="modal fade" id="tourModal{{ $tour->id }}" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $tour->title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <strong>Địa Điểm:</strong>
                                <p><i class="fas fa-map-pin text-danger"></i> {{ $tour->location }}</p>
                            </div>
                            <div class="mb-3">
                                <strong>Mô Tả:</strong>
                                <p>{{ $tour->description }}</p>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Giá Vé:</strong>
                                    <p class="text-primary" style="font-size: 18px; font-weight: 700;">
                                        {{ number_format($tour->price) }} ₫
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Thời Gian:</strong>
                                    <p>{{ $tour->duration }} ngày</p>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <strong>Ngày Khởi Hành:</strong>
                                    <p>{{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }}</p>
                                </div>
                                <div class="col-md-6">
                                    <strong>Ngày Kết Thúc:</strong>
                                    <p>{{ \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') }}</p>
                                </div>
                            </div>
                            <div class="alert alert-info mb-0">
                                <strong>Chỗ Trống:</strong> {{ $tour->available_slots }}/{{ $tour->slots }}
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                            @if($tour->available_slots > 0)
                            <a href="{{ route('bookings.create', $tour->id) }}" class="btn btn-primary">
                                <i class="fas fa-ticket"></i> Đặt Tour Ngay
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($tours->hasPages())
        <nav class="d-flex justify-content-center">
            <ul class="pagination">
                {{-- Previous Page Link --}}
                @if ($tours->onFirstPage())
                <li class="page-item disabled"><span class="page-link">← Trước</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $tours->previousPageUrl() }}"
                        rel="prev">← Trước</a></li>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($tours->getUrlRange(1, $tours->lastPage()) as $page => $url)
                @if ($page == $tours->currentPage())
                <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($tours->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $tours->nextPageUrl() }}"
                        rel="next">Tiếp →</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">Tiếp →</span></li>
                @endif
            </ul>
        </nav>
        @endif
        @else
        <div class="text-center py-5">
            <i class="fas fa-inbox" style="font-size: 60px; color: #999;"></i>
            <h3 class="mt-3">Không Có Tours Nào</h3>
            <p class="text-muted">Vui lòng quay lại sau để xem các tours mới!</p>
        </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
@endsection