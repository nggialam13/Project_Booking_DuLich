@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Đặt Tour</h2>

    <form action="{{ route('bookings.store') }}" method="POST">
        @csrf

        <input type="hidden" name="tour_id" value="{{ $tour->id }}">

        <div>
            <label>Tên tour:</label>
            <input type="text" value="{{ $tour->title }}" disabled>
        </div>

        <div>
            <label>Giá:</label>
            <input type="text" value="{{ number_format($tour->price) }} VNĐ" disabled>
        </div>

        <div>
            <label>Số chỗ còn:</label>
            <input type="text" value="{{ $tour->available_slots }}" disabled>
        </div>

        <div>
            <label>Số người:</label>
            <input type="number" name="quantity" min="1" required>
        </div>

        <button type="submit">Đặt Tour</button>
    </form>
</div>
@endsection