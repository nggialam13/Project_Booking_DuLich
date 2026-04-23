<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Các Tour Du Lịch</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px 0;
        }

        .navbar-custom {
            background: rgba(255, 255, 255, 0.95) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: #667eea !important;
            font-size: 24px;
        }

        .header-section {
            text-align: center;
            color: white;
            margin-bottom: 40px;
            padding: 40px 20px;
        }

        .header-section h1 {
            font-size: 48px;
            font-weight: 700;
            margin-bottom: 10px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .header-section p {
            font-size: 18px;
            opacity: 0.95;
        }

        .tour-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .tour-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 25px;
            margin-bottom: 50px;
        }

        .tour-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .tour-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
        }

        .tour-image {
            width: 100%;
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 60px;
        }

        .tour-body {
            padding: 20px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .tour-location {
            color: #667eea;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .tour-title {
            font-size: 18px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .tour-description {
            font-size: 14px;
            color: #666;
            margin-bottom: 15px;
            flex-grow: 1;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .tour-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .info-label {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-size: 16px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .info-value.price {
            color: #667eea;
            font-size: 18px;
        }

        .info-value.duration {
            color: #764ba2;
        }

        .tour-slots {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 6px;
        }

        .slots-info {
            font-size: 14px;
            font-weight: 600;
            color: #1a1a1a;
        }

        .slots-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .slots-badge.full {
            background: #ffe0e0;
            color: #dc3545;
        }

        .slots-badge.available {
            background: #e0ffe0;
            color: #28a745;
        }

        .slots-badge.limited {
            background: #fff3cd;
            color: #ff9800;
        }

        .tour-dates {
            font-size: 13px;
            color: #999;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .tour-footer {
            display: flex;
            gap: 10px;
            margin-top: auto;
        }

        .btn-custom {
            flex: 1;
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .btn-info {
            background: #f0f0f0;
            color: #1a1a1a;
            flex: 1;
        }

        .btn-info:hover {
            background: #e0e0e0;
            color: #1a1a1a;
            text-decoration: none;
        }

        .btn-book {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            flex: 1.5;
        }

        .btn-book:hover {
            transform: scale(1.05);
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-book:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
            opacity: 0.6;
        }

        .filter-section {
            background: white;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 30px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .filter-section h5 {
            margin-bottom: 15px;
            font-weight: 700;
            color: #1a1a1a;
        }

        .search-box {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #eee;
            border-radius: 6px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        .search-box:focus {
            outline: none;
            border-color: #667eea;
        }

        .no-tours {
            text-align: center;
            padding: 60px 20px;
            color: white;
        }

        .no-tours i {
            font-size: 60px;
            margin-bottom: 20px;
            opacity: 0.8;
        }

        .no-tours h3 {
            font-size: 24px;
            margin-bottom: 10px;
        }

        .pagination {
            justify-content: center;
            margin-top: 40px;
        }

        .pagination .page-link {
            background: white;
            border: 1px solid #ddd;
            color: #667eea;
        }

        .pagination .page-link:hover {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .pagination .page-item.active .page-link {
            background: #667eea;
            border-color: #667eea;
        }

        .tour-status {
            position: absolute;
            top: 10px;
            right: 10px;
            padding: 6px 12px;
            background: #28a745;
            color: white;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .tour-card-wrapper {
            position: relative;
        }

        @media (max-width: 768px) {
            .header-section h1 {
                font-size: 32px;
            }

            .tour-grid {
                grid-template-columns: 1fr;
            }

            .tour-info {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-light navbar-custom sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <i class="fas fa-map-location-dot"></i> TourDuLich
            </a>
            <div>
                @auth
                    <a href="{{ route('bookings.list') }}" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-clipboard-list"></i> Đơn Đặt Tour
                    </a>
                    <a href="/logout" class="btn btn-sm btn-outline-danger" 
                        onclick="document.getElementById('logout-form').submit(); return false;">
                        <i class="fas fa-sign-out-alt"></i> Đăng Xuất
                    </a>
                    <form id="logout-form" action="/logout" method="POST" style="display:none;">
                        @csrf
                    </form>
                @else
                    <a href="/login" class="btn btn-sm btn-outline-primary me-2">
                        <i class="fas fa-sign-in-alt"></i> Đăng Nhập
                    </a>
                    <a href="/register" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus"></i> Đăng Ký
                    </a>
                @endauth
            </div>
        </div>
    </nav>

    <div class="header-section">
        <h1><i class="fas fa-plane"></i> Khám Phá Tours Du Lịch</h1>
        <p>Chọn từ hàng trăm tours tuyệt vời và tạo kỷ niệm đáng nhớ</p>
    </div>

    <div class="tour-container">
        @if($tours->count() > 0)
            <div class="filter-section">
                <h5><i class="fas fa-filter"></i> Tìm Tour</h5>
                <form method="GET" class="d-flex gap-2" id="searchForm">
                    <input type="text" name="search" class="search-box" placeholder="Tìm theo tên tour hoặc địa điểm..."
                        value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary" style="white-space: nowrap;">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </form>
            </div>

            <div class="tour-grid">
                @foreach($tours as $tour)
                    <div class="tour-card-wrapper">
                        <div class="tour-card">
                            <div style="position: relative;">
                                @if($tour->image)
                                    <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}"
                                        class="tour-image" style="object-fit: cover; height: auto;">
                                @else
                                    <div class="tour-image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                                <span class="tour-status">Còn Chỗ</span>
                            </div>

                            <div class="tour-body">
                                <div class="tour-location">
                                    <i class="fas fa-map-pin"></i> {{ $tour->location }}
                                </div>

                                <h3 class="tour-title">{{ $tour->title }}</h3>

                                <p class="tour-description">{{ $tour->description }}</p>

                                <div class="tour-info">
                                    <div class="info-item">
                                        <span class="info-label">Giá Vé</span>
                                        <span class="info-value price">{{ number_format($tour->price) }} ₫</span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label">Thời Gian</span>
                                        <span class="info-value duration">{{ $tour->duration }} ngày</span>
                                    </div>
                                </div>

                                <div class="tour-dates">
                                    <i class="fas fa-calendar-alt"></i>
                                    {{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }} -
                                    {{ \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') }}
                                </div>

                                <div class="tour-slots">
                                    <span class="slots-info">
                                        <i class="fas fa-users"></i> Chỗ còn trống
                                    </span>
                                    @if($tour->available_slots === 0)
                                        <span class="slots-badge full">Hết Chỗ</span>
                                    @elseif(($tour->available_slots / $tour->slots) * 100 <= 20)
                                        <span class="slots-badge limited">{{ $tour->available_slots }}/{{ $tour->slots }}</span>
                                    @else
                                        <span class="slots-badge available">{{ $tour->available_slots }}/{{ $tour->slots }}</span>
                                    @endif
                                </div>

                                <div class="tour-footer">
                                    <button class="btn-custom btn-info" data-bs-toggle="modal"
                                        data-bs-target="#tourModal{{ $tour->id }}">
                                        <i class="fas fa-info-circle"></i> Chi Tiết
                                    </button>
                                    @if($tour->available_slots > 0)
                                        <a href="{{ route('bookings.create', $tour->id) }}" class="btn-custom btn-book">
                                            <i class="fas fa-ticket"></i> Đặt Ngay
                                        </a>
                                    @else
                                        <button class="btn-custom btn-book" disabled>
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
                                <div class="modal-header border-0 pb-0">
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
                                    <div class="alert alert-info">
                                        <strong>Chỗ Trống:</strong> {{ $tour->available_slots }}/{{ $tour->slots }}
                                    </div>
                                </div>
                                <div class="modal-footer border-0 pt-0">
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
            <div class="no-tours">
                <i class="fas fa-inbox"></i>
                <h3>Không Có Tours Nào</h3>
                <p>Vui lòng quay lại sau để xem các tours mới!</p>
            </div>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
