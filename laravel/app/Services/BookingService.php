<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class BookingService
{
    // Query service methods: keep read/list logic out of controller.
    public function getUserBookingsPaginated(int $userId, int $perPage = 6): LengthAwarePaginator
    {
        return Booking::with('tour')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getUserBookingDetail(int $userId, int $bookingId): Booking
    {
        return Booking::with(['tour', 'bookingDetail'])
            ->where('user_id', $userId)
            ->findOrFail($bookingId);
    }

    public function getAdminBookingsPaginated(?string $status, int $perPage = 10): LengthAwarePaginator
    {
        $query = Booking::with(['tour', 'user', 'bookingDetail'])->latest();

        if (in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
            $query->where('status', $status);
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function createBooking(int $userId, int $tourId, int $quantity): Booking
    {
        return DB::transaction(function () use ($userId, $tourId, $quantity) {
            // Lock tour row to prevent overbooking under concurrency.
            $tour = Tour::where('id', $tourId)->lockForUpdate()->firstOrFail();

            if ($tour->available_slots < $quantity) {
                throw new \RuntimeException('Không đủ chỗ');
            }

            $booking = Booking::create([
                'user_id' => $userId,
                'tour_id' => $tour->id,
                'booking_date' => now(),
                'total_price' => $tour->price * $quantity,
                'status' => 'pending',
            ]);

            BookingDetail::create([
                'booking_id' => $booking->id,
                'quantity' => $quantity,
                'price' => $tour->price,
            ]);

            $tour->decrement('available_slots', $quantity);

            return $booking;
        });
    }

    public function cancelByUser(int $bookingId, int $userId): void
    {
        $booking = Booking::where('user_id', $userId)->findOrFail($bookingId);
        $this->cancelBooking($booking->id);
    }

    public function cancelByAdmin(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);
        $this->cancelBooking($booking->id);
    }

    public function confirm(int $bookingId): void
    {
        $booking = Booking::findOrFail($bookingId);

        if ($booking->status !== 'pending') {
            throw new \RuntimeException('Không thể xác nhận booking này');
        }

        $booking->update(['status' => 'confirmed']);
    }

    private function cancelBooking(int $bookingId): void
    {
        DB::transaction(function () use ($bookingId) {
            // Lock booking row to avoid double-cancel race condition.
            $booking = Booking::where('id', $bookingId)->lockForUpdate()->firstOrFail();

            if ($booking->status === 'cancelled') {
                throw new \RuntimeException('Booking đã bị hủy');
            }

            $booking->load('bookingDetail');
            $quantity = (int) optional($booking->bookingDetail)->quantity;

            $booking->update(['status' => 'cancelled']);

            if ($quantity > 0) {
                $tour = Tour::where('id', $booking->tour_id)->lockForUpdate()->first();
                if ($tour) {
                    $tour->increment('available_slots', $quantity);
                }
            }
        });
    }
}