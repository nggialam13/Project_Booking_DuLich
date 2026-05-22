@extends('layouts.admin')

@section('content')
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="admin-tour-page">
    <div class="my-5 px-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Danh sách Tours</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('tours.create') }}" class="btn btn-primary">+ Tạo Tour Mới</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8 col-xl-8">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-dark">
                            <tr>
                                <th>ID</th>
                                <th>Tiêu đề</th>
                                <th>Địa điểm</th>
                                <th>Giá (VND)</th>
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
                            'duration' => $tour->duration . ' ngày',
                            'start' => \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y'),
                            'end' => \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y'),
                            'slots' => ($tour->slots - $tour->available_slots) . '/' . $tour->slots . ' chỗ',
                            'status' => $tour->status === 'active' ? 'Hoạt động' : 'Không hoạt động',
                            'image' => $previewImageUrl ?? '',
                            ];
                            @endphp
                            <tr @if($tour->status==='inactive') class="table-danger" @endif
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
                                <td>
                                    <span>{{ $tour->duration }} ngày</span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($tour->start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($tour->end_date)->format('d/m/Y') }}
                                    </small>
                                </td>
                                <td>
                                    <span class="badge 
                                    @if($tour->available_slots===0) bg-danger 
                                    @elseif((($tour->slots-$tour->available_slots) / $tour->slots) * 100 >= 80) bg-warning 
                                    @else bg-info @endif">{{ $tour->slots - $tour->available_slots }}/{{ $tour->slots }}
                                    </span>
                                </td>
                                <td style="white-space: nowrap;">
                                    <button type="button"
                                        class="btn btn-sm {{$tour->status==="active"?'btn-success':'btn-danger'}}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#toggleStatusModal{{ $tour->id }}">
                                        <i class="fas fa-exchange-alt"></i> {{ $tour->status==="active"?'Hoạt động':'Không hoạt động' }}
                                    </button>
                                </td>
                                <td style="white-space: nowrap;">
                                    <a href="{{ route('tours.edit', $tour->id) }}" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Sửa
                                    </a>
                                    <button type="button"
                                        class="btn btn-sm btn-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteTourModal{{ $tour->id }}">
                                        <i class="fas fa-trash"></i> Xóa
                                    </button>
                                </td>
                            </tr>
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
                                            <form method="POST" action="{{ route('tours.toggle-status', $tour->id) }}">
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
                                            Bạn chắc chắn muốn xóa tour <strong>{{ $tour->title }}</strong>?
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                            <form method="POST" action="{{ route('tours.destroy', $tour->id) }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">Xóa</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <p class="text-muted">Không có tour nào.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-4 col-xl-4">
                <div class="tour-preview-wrapper">
                    <div class="tour-preview-panel" aria-live="polite">
                        <div class="tour-preview-image" id="tourPreviewImage">
                            <span>Không có ảnh</span>
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

        @if($tours->hasPages())
        <nav class="mt-5">
            <ul class="pagination justify-content-center">
                {{-- Previous Page Link --}}
                @if ($tours->onFirstPage())
                <li class="page-item disabled"><span class="page-link">← Trước</span></li>
                @else
                <li class="page-item"><a class="page-link" href="{{ $tours->previousPageUrl() }}" rel="prev">← Trước</a></li>
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
                <li class="page-item"><a class="page-link" href="{{ $tours->nextPageUrl() }}" rel="next">Sau →</a></li>
                @else
                <li class="page-item disabled"><span class="page-link">Sau →</span></li>
                @endif
            </ul>
        </nav>
        @endif
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
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

        function setPreview(row) {
            const data = row.dataset;
            const label = (text) => {
                return `<span class="tour-preview-label">${text}:</span>`;
            };

            const setText = (el, value, labelText) => {
                el.innerHTML = value ? `${label(labelText)} ${value}` : '';
            };

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
                preview.image.innerHTML = '<span>Không có ảnh</span>';
            }
        }

        rows.forEach((row) => {
            row.addEventListener('mouseenter', () => setPreview(row));
        });

        if (rows.length > 0) {
            setPreview(rows[0]);
        }
    </script>
</body>

</html>
@endsection