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
                    <tr @if($tour->available_slots===0) class="table-danger" @endif>
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
                            <form method="POST" action="{{ route('tours.toggle-status', $tour->id) }}" style="display:inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm  {{$tour->status==="active"?'btn-success':'btn-danger'}}" onclick="return confirm('Bạn chắc chắn sửa tour này?')">
                                    <i class="fas fa-exchange-alt"></i> {{ $tour->status==="active"?'Hoạt động':'Không hoạt động' }}
                                </button>
                            </form>
                        </td>
                        <td style="white-space: nowrap;">
                            <a href="{{ route('tours.edit', $tour->id) }}" class="btn btn-sm btn-warning">
                                <i class="fas fa-edit"></i> Sửa
                            </a>
                            <form method="POST" action="{{ route('tours.destroy', $tour->id) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Bạn chắc chắn muốn xóa tour này?')">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </form>

                        </td>
                    </tr>
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