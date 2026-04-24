<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Tour;

class BookingService
{
    public function create($userId, $tourId, $quantity)
    {
        $tour = Tour::findOrFail($tourId);

        // ❗ check slot
        if ($tour->available_slots < $quantity) {
            throw new \Exception('Không đủ chỗ');
        }

        // tính tiền
        $total = $tour->price * $quantity;

        // tạo booking
        $booking = Booking::create([
            'user_id' => $userId,
            'tour_id' => $tourId,
            'booking_date' => now(),
            'total_price' => $total,
            'status' => 'pending'
        ]);

        // tạo booking detail
        BookingDetail::create([
            'booking_id' => $booking->id,
            'quantity' => $quantity,
            'price' => $tour->price
        ]);

        // trừ slot
        $tour->decrement('available_slots', $quantity);

        return $booking;
    }
    public function store(Request $request)
{
    if (!auth()->check()) {
        return redirect('/login')->with('error', 'Bạn cần đăng nhập');
    }

    $data = $request->validate([
        'tour_id' => 'required|exists:tours,id',
        'quantity' => 'required|integer|min:1'
    ]);

    $booking = $this->bookingService->create(
        auth()->id(), // 🔥 chỗ này phải có giá trị
        $data['tour_id'],
        $data['quantity']
    );

    return redirect('/payments')->with('success', 'Đặt tour thành công');
}
}