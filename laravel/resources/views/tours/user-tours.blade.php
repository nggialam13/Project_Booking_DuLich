 @extends('layouts.master')

 @section('content')


 <div class="container mb-5">
     <!-- Search Section -->
     @if($tours->count() > 0)
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Tìm Tour</h5>
            <form method="GET" class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small text-muted mb-1">Tiêu đề</label>
                    <input type="text" name="title" class="form-control"
                        placeholder="Nhập tên tour" value="{{ request('title') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Giá từ</label>
                    <input type="number" name="price_min" class="form-control" min="0" step="1"
                        placeholder="Từ" value="{{ request('price_min') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Giá đến</label>
                    <input type="number" name="price_max" class="form-control" min="0" step="1"
                        placeholder="Đến" value="{{ request('price_max') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Ngày từ</label>
                    <input type="number" name="days_min" class="form-control" min="1" step="1"
                        placeholder="Từ" value="{{ request('days_min') }}">
                </div>
                <div class="col-6 col-md-2">
                    <label class="form-label small text-muted mb-1">Ngày đến</label>
                    <input type="number" name="days_max" class="form-control" min="1" step="1"
                        placeholder="Đến" value="{{ request('days_max') }}">
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Tìm
                    </button>
                </div>
                <div class="col-6 col-md-2 d-grid">
                    <a href="{{ route('tours.user-index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-rotate-right"></i> Làm mới
                    </a>
                </div>
            </form>
        </div>
    </div>

     <!-- Tour Grid -->
     <div class="row g-4 mb-5">
         @foreach($tours as $tour)
         <div class="col-md-6 col-lg-4">
             <div class="card h-100 shadow-sm tour-card">
                 <!-- Image -->
                 <div style="position: relative; height: 220px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); overflow: hidden; cursor: pointer;"
                     role="button"
                     data-bs-toggle="modal"
                     data-bs-target="#tourModal{{ $tour->id }}">
                     @if($tour->image&&Storage::disk('public')->exists($tour->image))
                     <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}"
                         class="card-img-top" style="height: 100%; object-fit: cover;">
                     @elseif($tour->image&&Storage::disk('public')->exists('demo/'.$tour->image))
                     <img src="{{ asset('storage/demo/' . $tour->image) }}" alt="{{ $tour->title }}"
                         class="card-img-top" style="height: 100%; object-fit: cover;">
                     @else
                     <div class="d-flex align-items-center justify-content-center h-100 text-white">
                         <i class="fas fa-image" style="font-size: 60px;"></i>
                     </div>
                     @endif
                 </div>

                 <!-- Content -->
                 <div class="card-body d-flex flex-column">
                     <p class="text-primary mb-2">
                         <i class="fas fa-map-pin"></i> {{ $tour->location }}
                     </p>

                     <h5 class="card-title mb-2">{{ $tour->title }}</h5>

                     <p class="card-text text-muted small mb-3" style="overflow: hidden; display: -webkit-box; line-clamp: 2; -webkit-box-orient: vertical;">
                         {{ $tour->description }}
                     </p>

                     <!-- Info Grid -->
                     <div class="row mb-3 pb-3 border-bottom">
                         <div class="col-6">
                             <small class="text-muted d-block">Giá Vé</small>
                             <strong class="text-primary">{{ number_format($tour->price) }} VND</strong>
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
                             <span class="badge bg-warning">{{ $tour->slots-$tour->available_slots }}/{{ $tour->slots }}</span>
                             @else
                             <span class="badge bg-success">{{ $tour->slots-$tour->available_slots }}/{{ $tour->slots }}</span>
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
                        <div class="mb-4">
                            @if($tour->image&&Storage::disk('public')->exists($tour->image))
                            <img src="{{ asset('storage/' . $tour->image) }}" alt="{{ $tour->title }}"
                                class="img-fluid rounded w-100" style="max-height: 320px; object-fit: cover;">
                            @elseif($tour->image&&Storage::disk('public')->exists('demo/'.$tour->image))
                            <img src="{{ asset('storage/demo/' . $tour->image) }}" alt="{{ $tour->title }}"
                                class="img-fluid rounded w-100" style="max-height: 320px; object-fit: cover;">
                            @else
                            <div class="d-flex align-items-center justify-content-center bg-light rounded" style="height: 220px;">
                                <i class="fas fa-image text-muted" style="font-size: 48px;"></i>
                            </div>
                            @endif
                        </div>
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
                                     {{ number_format($tour->price) }} VND
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
         <div class="d-flex justify-content-center gap-2">
             <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                 <i class="fas fa-arrow-left"></i> Quay lại
             </a>
             <a href="{{ route('tours.user-index') }}" class="btn btn-primary">
                 <i class="fas fa-rotate-right"></i> Làm mới
             </a>
         </div>
     </div>
     @endif
 </div>


 @endsection