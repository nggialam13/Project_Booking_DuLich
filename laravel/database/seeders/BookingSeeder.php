<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookingDetail;
use App\Models\Tour;
class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run()
{
    // Lấy tour (phải có sẵn 1 tour)
    $tour = Tour::first();

    if (!$tour) {
        return;
    }

    // ===== BOOKING 1: PENDING =====
    $booking1 = Booking::create([
        'user_id' => 1,
        'tour_id' => $tour->id,
        'booking_date' => now(),
        'total_price' => 2000000,
        'status' => 'pending'
    ]);

    BookingDetail::create([
        'booking_id' => $booking1->id,
        'quantity' => 2,
        'price' => 1000000
    ]);

    // ===== BOOKING 2: CONFIRMED =====
    $booking2 = Booking::create([
        'user_id' => 1,
        'tour_id' => $tour->id,
        'booking_date' => now(),
        'total_price' => 1000000,
        'status' => 'confirmed'
    ]);

    BookingDetail::create([
        'booking_id' => $booking2->id,
        'quantity' => 1,
        'price' => 1000000
    ]);

    // ===== BOOKING 3: CANCELLED =====
    $booking3 = Booking::create([
        'user_id' => 1,
        'tour_id' => $tour->id,
        'booking_date' => now(),
        'total_price' => 3000000,
        'status' => 'cancelled'
    ]);

    BookingDetail::create([
        'booking_id' => $booking3->id,
        'quantity' => 3,
        'price' => 1000000
    ]);

    // ===== UPDATE SLOT CHUẨN LOGIC =====
    // chỉ trừ pending + confirmed
    $usedSlots = 2 + 1;

    $tour->update([
        'available_slots' => $tour->slots - $usedSlots
    ]);
}
}
