@extends('layouts.admin')

@section('content')
@php
use Illuminate\Support\Facades\Storage;
@endphp

<style>
    .tour-preview-wrapper {
        position: sticky;
        top: 1rem;
    }

    .tour-preview-panel {
        background: #fff;
        border-radius: 12px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.06);
        padding: 16px;
    }

    .tour-preview-image {
        width: 100%;
        height: 220px;
        border-radius: 10px;
        background: #f1f5f9;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        margin-bottom: 12px;
    }

    .tour-preview-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .tour-preview-title {
        font-weight: 600;
        font-size: 16px;
        color: #1f2937;
        margin-bottom: 8px;
    }

    .tour-preview-meta {
        font-size: 13px;
        color: #475569;
        margin-bottom: 6px;
    }

    .tour-preview-desc {
        font-size: 13px;
        color: #374151;
        margin-top: 10px;
        max-height: 120px;
        overflow-y: auto;
    }

    .tour-preview-label {
        font-weight: 600;
        color: #1f2937;
    }

    @media (max-width: 991px) {
        .tour-preview-wrapper {
            position: static;
            margin-top: 1.5rem;
        }
    }
</style>

<div class="container-fluid px-0 admin-tours-page">
    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
        <div>
            <h2 class="mb-1"><i class="fas fa-map-location-dot me-2"></i>Quản lý Tours</h2>
            <p class="text-muted mb-0">Danh sách tour, trạng thái và chỗ trống</p>
        </div>
        <div>
            <a href="{{ route('tours.create') }}" class="btn btn-admin-primary">
                <i class="fas fa-plus me-1"></i> Tạo tour mới
            </a>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title mb-3"><i class="fas fa-filter"></i> Tìm Tour</h5>
            @include('tours._search-form', ['action' => route('tours.index'), 'reset' => route('tours.index')])
        </div>
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="card card-admin mb-0">
                <div class="card-admin-body p-0">
                    <div class="table-responsive">
                        <table class="table table-admin mb-0">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tiêu đề</th>
                                    <th>Địa điểm</th>
                                    <th>Giá </th>
                                    <th>Thời gian (ngày)</th>
                                    <th>Lịch trình</th>
                                    <th>Chỗ trống</th>
                                    <th>Trạng thái</th>
                                    <th>Hành động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($tours as $tour)
                                @php
                                $previewImageUrl = null;
                                if ($tour->image && Storage::disk('public')->exists($tour->image)) {
                                $previewImageUrl = asset('storage/' . $tour->image);
                                } elseif ($tour->image && Storage::disk('public')->exists('demo/' . $tour->image)) {
                                $previewImageUrl = asset('storage/demo/' . $tour->image);
                                }
                                $previewData = [
                                'title' => $tour->title,
                                'description' => $tour->description,
                                'location' => $tour->location,
                                'price' => number_format($tour->price) . ' VND',
                                'duration' => $tour->duration,
                                'start' => \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y'),
                                'end' => \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y'),
                                'slots' => ($tour->slots - $tour->available_slots) . '/' . $tour->slots . ' chỗ',
                                'status' => $tour->status === 'active' ? 'Hoạt động' : 'Không hoạt động',
                                'image' => $previewImageUrl ?? '',
                                ];
                                @endphp
                                <tr @if($tour->status === 'inactive') class="table-danger" @endif
                                    data-preview-title="{{ e($previewData['title']) }}"
                                    data-preview-description="{{ e($previewData['description']) }}"
                                    data-preview-location="{{ e($previewData['location']) }}"
                                    data-preview-price="{{ e($previewData['price']) }}"
                                    data-preview-duration="{{ e($previewData['duration']) }}"
                                    data-preview-start="{{ e($previewData['start']) }}"
                                    data-preview-end="{{ e($previewData['end']) }}"
                                    data-preview-slots="{{ e($previewData['slots']) }}"
                                    data-preview-status="{{ e($previewData['status']) }}"
                                    data-preview-image="{{ e($previewData['image']) }}">
                                    <td>{{ $tour->id }}</td>
                                    <td>{{ $tour->title }}</td>
                                    <td>{{ $tour->location }}</td>
                                    <td>{{ number_format($tour->price) }}</td>
                                    <td>{{ $tour->duration }} ngày</td>
                                    <td class="text-nowrap">
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }}
                                            –
                                            {{ \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') }}
                                        </small>
                                    </td>
                                    <td>
                                        <span class="badge
                                            @if($tour->available_slots === 0) bg-danger
                                            @elseif($tour->slots > 0 && ((($tour->slots - $tour->available_slots) / $tour->slots) * 100) >= 80) bg-warning
                                            @else bg-info @endif">
                                            {{ $tour->slots - $tour->available_slots }}/{{ $tour->slots }}
                                        </span>
                                    </td>
                                    <td class="text-nowrap">
                                        <button type="button"
                                            class="btn btn-sm {{ $tour->status === 'active' ? 'btn-success' : 'btn-danger' }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#toggleStatusModal{{ $tour->id }}">
                                            <i class="fas fa-exchange-alt"></i>
                                            {{ $tour->status === 'active' ? 'Hoạt động' : 'Tắt' }}
                                        </button>
                                    </td>
                                    <td class="text-nowrap">
                                        <a href="{{ route('tours.edit', $tour->id) }}" class="btn btn-sm btn-warning">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button"
                                            class="btn btn-sm btn-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteTourModal{{ $tour->id }}"
                                            @if(($tour->slots - $tour->available_slots) > 0) disabled title="Tour đã có người đặt, không thể xóa" @endif>
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-5 text-muted">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Không có tour nào.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            @if($tours->hasPages())
            <nav class="mt-4">
                <ul class="pagination justify-content-center mb-0">
                    @if ($tours->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">← Trước</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $tours->previousPageUrl() }}">← Trước</a></li>
                    @endif

                    @foreach ($tours->getUrlRange(1, $tours->lastPage()) as $page => $url)
                    @if ($page == $tours->currentPage())
                    <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                    <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                    @endforeach

                    @if ($tours->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $tours->nextPageUrl() }}">Sau →</a></li>
                    @else
                    <li class="page-item disabled"><span class="page-link">Sau →</span></li>
                    @endif
                </ul>
            </nav>
            @endif
        </div>

        <div class="col-lg-4">
            <div class="tour-preview-wrapper">
                <div class="tour-preview-panel" aria-live="polite">
                    <div class="tour-preview-image" id="tourPreviewImage">
                        <span class="text-muted">Không có ảnh</span>
                    </div>
                    <div class="tour-preview-title" id="tourPreviewTitle">Chọn tour để xem</div>
                    <div class="tour-preview-meta" id="tourPreviewLocation"></div>
                    <div class="tour-preview-meta" id="tourPreviewDates"></div>
                    <div class="tour-preview-meta" id="tourPreviewDuration"></div>
                    <div class="tour-preview-meta" id="tourPreviewPrice"></div>
                    <div class="tour-preview-meta" id="tourPreviewSlots"></div>
                    <div class="tour-preview-meta" id="tourPreviewStatus"></div>
                    <div class="tour-preview-desc" id="tourPreviewDescription"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($tours as $tour)
<div class="modal fade" id="toggleStatusModal{{ $tour->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi trạng thái tour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn muốn chuyển trạng thái tour <strong>{{ $tour->title }}</strong>?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form method="POST" action="{{ route('tours.toggle-status', $tour->id) }}" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="deleteTourModal{{ $tour->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xóa tour</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                @if(($tour->slots - $tour->available_slots) > 0)
                Tour <strong>{{ $tour->title }}</strong> đã có người đặt nên không thể xóa.
                @else
                Bạn chắc chắn muốn xóa tour <strong>{{ $tour->title }}</strong>?
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                @if(($tour->slots - $tour->available_slots) === 0)
                <form method="POST" action="{{ route('tours.destroy', $tour->id) }}" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endforeach

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rows = document.querySelectorAll('tr[data-preview-image]');
        const preview = {
            image: document.querySelector('#tourPreviewImage'),
            title: document.querySelector('#tourPreviewTitle'),
            location: document.querySelector('#tourPreviewLocation'),
            dates: document.querySelector('#tourPreviewDates'),
            duration: document.querySelector('#tourPreviewDuration'),
            price: document.querySelector('#tourPreviewPrice'),
            slots: document.querySelector('#tourPreviewSlots'),
            status: document.querySelector('#tourPreviewStatus'),
            description: document.querySelector('#tourPreviewDescription'),
        };

        if (!preview.image) return;

        const label = (text) => `<span class="tour-preview-label">${text}:</span>`;

        const setText = (el, value, labelText) => {
            el.innerHTML = value ? `${label(labelText)} ${value}` : '';
        };

        function setPreview(row) {
            const data = row.dataset;
            preview.title.textContent = data.previewTitle || 'Chọn tour để xem';
            setText(preview.location, data.previewLocation, 'Địa điểm');
            setText(
                preview.dates,
                data.previewStart && data.previewEnd ? `${data.previewStart} - ${data.previewEnd}` : '',
                'Lịch trình'
            );
            setText(preview.duration, data.previewDuration, 'Thời gian');
            setText(preview.price, data.previewPrice, 'Giá');
            setText(preview.slots, data.previewSlots, 'Đã đặt');
            setText(preview.status, data.previewStatus, 'Trạng thái');
            preview.description.textContent = data.previewDescription || '';

            if (data.previewImage) {
                preview.image.innerHTML = `<img src="${data.previewImage}" alt="${data.previewTitle}">`;
            } else {
                preview.image.innerHTML = '<span class="text-muted">Không có ảnh</span>';
            }
        }

        rows.forEach((row) => row.addEventListener('mouseenter', () => setPreview(row)));

        if (rows.length > 0) {
            setPreview(rows[0]);
        }
    });
</script>
@endsection