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

   public function getAdminBookingsPaginated($status = null, $keyword = null)
{
    return Booking::with(['user', 'tour', 'bookingDetail'])

        // 🔍 SEARCH
        ->when($keyword, function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {

                $q->whereHas('user', function ($q2) use ($keyword) {
                    $q2->where('name', 'like', "%$keyword%");
                })

                ->orWhereHas('tour', function ($q2) use ($keyword) {
                    $q2->where('title', 'like', "%$keyword%");
                });

            });
        })

        // 🎯 FILTER STATUS
        ->when(in_array($status, ['pending', 'confirmed', 'cancelled']), function ($query) use ($status) {
            $query->where('status', $status);
        })

        ->latest()
        ->paginate(10);
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