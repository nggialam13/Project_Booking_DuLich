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

    public function cancel($bookingId)
    {
        $booking = Booking::with('bookingDetail', 'tour')->findOrFail($bookingId);

        // ❗ chỉ cho hủy khi chưa confirm
        if ($booking->status !== 'pending') {
            throw new \Exception('Không thể hủy booking này');
        }

        // đổi trạng thái
        $booking->update([
            'status' => 'cancelled'
        ]);

        // restore slot
        $quantity = $booking->bookingDetail->quantity;

        $booking->tour->increment('available_slots', $quantity);

        return true;
    }

    public function confirm($bookingId)
    {
        $booking = Booking::with('bookingDetail', 'tour')->findOrFail($bookingId);
        // chỉ cho confirm khi pending
        if ($booking->status !== 'pending') {
            throw new \Exception('Không thể xác nhận booking này');
        }

        $booking->update([
            'status' => 'confirmed'
        ]);

        return true;
    }

   



}