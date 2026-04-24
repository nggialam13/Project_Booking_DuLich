<!-- @extends('layouts.app')

@section('content') -->

<!-- 🔥 HERO -->
<!-- <div class="hero-section text-white text-center d-flex align-items-center justify-content-center">
    <div>
        <h1 class="display-3 fw-bold mb-3">🌍 Khám Phá BOOKING TOUR</h1>
        <p class="lead mb-4">Đặt tour du lịch nhanh chóng – giá tốt – trải nghiệm tuyệt vời</p>

        <!-- SEARCH -->
        <form class="d-flex justify-content-center">
            <input type="text" class="form-control w-50 me-2" placeholder="Tìm tour, địa điểm...">
            <button class="btn btn-primary px-4">Tìm</button>
        </form>
    </div>
</div>

<!-- 🔥 CATEGORY -->
<div class="container my-5">
    <h2 class="text-center mb-5 fw-bold">✨ Loại Hình Du Lịch</h2>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card category-card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-route fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Tour Phượt</h5>
                    <p class="text-muted">Khám phá những cung đường mạo hiểm.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card category-card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-umbrella-beach fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Tour Biển</h5>
                    <p class="text-muted">Thư giãn bên bờ biển tuyệt đẹp.</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card category-card text-center h-100 shadow-sm">
                <div class="card-body">
                    <i class="fas fa-mountain fa-3x text-primary mb-3"></i>
                    <h5 class="fw-bold">Tour Núi</h5>
                    <p class="text-muted">Chinh phục đỉnh núi và ngắm cảnh.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 🔥 TOUR NỔI BẬT -->
<div class="container mb-5">
    <h2 class="text-center mb-4 fw-bold">🔥 Tour Nổi Bật</h2>

    <div class="row g-4">
        @forelse($tours ?? [] as $tour)
        <div class="col-md-4">
            <div class="card tour-card h-100 shadow-sm">

                <img src="{{ asset('storage/'.$tour->image) }}"
                     class="card-img-top"
                     style="height:200px; object-fit:cover;">

                <div class="card-body">
                    <h5 class="fw-bold">{{ $tour->title }}</h5>

                    <p class="text-muted small">
                        <i class="fas fa-map-marker-alt"></i> {{ $tour->location }}
                    </p>

                    <p class="text-danger fw-bold">
                        {{ number_format($tour->price) }} VND
                    </p>

                    <a href="/tours/{{ $tour->id }}" class="btn btn-primary w-100">
                        Xem chi tiết
                    </a>
                </div>

            </div>
        </div>
        @empty
        <p class="text-center text-muted">Chưa có tour nào</p>
        @endforelse
    </div>
</div> -->

<!-- @endsection -->