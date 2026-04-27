@extends('layouts.master')

@section('content')

<h3 class="section-title mb-4">📊 Quản lý Booking</h3>

<div class="card card-custom p-3">

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>User</th>
                <th>Tour</th>
                <th>Tiền</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>

        <tbody>
            @foreach($bookings as $booking)
            <tr>

                <td>{{ $booking->user->name }}</td>

                <td>{{ $booking->tour->title }}</td>

                <td class="text-primary fw-bold">
                    {{ number_format($booking->total_price) }}
                </td>

                <td>
                    <span class="badge bg-info">
                        {{ $booking->status }}
                    </span>
                </td>

                <td class="d-flex gap-2">

                    <form action="/admin/bookings/confirm/{{ $booking->id }}" method="POST">
                        @csrf
                        <button class="btn btn-success btn-sm">
                            ✔ Confirm
                        </button>
                    </form>

                    <form action="/admin/bookings/cancel/{{ $booking->id }}" method="POST">
                        @csrf
                        <button class="btn btn-danger btn-sm">
                            ✖ Cancel
                        </button>
                    </form>

                </td>

            </tr>
            @endforeach
        </tbody>

    </table>

</div>

@endsection