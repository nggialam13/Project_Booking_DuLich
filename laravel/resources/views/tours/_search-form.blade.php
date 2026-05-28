<form method="GET" action="{{ $action ?? url()->current() }}" class="row g-2 align-items-end">
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
        <a href="{{ $reset ?? url()->current() }}" class="btn btn-outline-secondary">
            <i class="fas fa-rotate-right"></i> Làm mới
        </a>
    </div>
</form>
