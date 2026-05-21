@extends('layouts.admin')

@section('content')
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý Tours</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <div class="row mb-4">
            <div class="col-md-6">
                <h1>Danh sách Tours</h1>
            </div>
            <div class="col-md-6 text-end">
                <a href="{{ route('tours.create') }}" class="btn btn-primary">+ Tạo Tour Mới</a>
            </div>
        </div>

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
                    <tr @if($tour->status==='inactive') class="table-danger" @endif>
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
</body>

</html>
@endsection