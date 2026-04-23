<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;
use App\Models\Booking;
use App\Models\BookingDetail;

class BookingController extends Controller
{


    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);

        return view('bookings.create', compact('tour'));
    }
    public function store(Request $request)
    {
        // 1. Validate
        $data = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // 2. Lấy tour
        $tour = Tour::findOrFail($data['tour_id']);

        // 3. Check slot
        if ($data['quantity'] > $tour->available_slots) {
            return back()->with('error', 'Không đủ chỗ');
        }

        // 4. Tính tổng tiền
        $total_price = $tour->price * $data['quantity'];

        // 5. Tạo booking
        $booking = Booking::create([
            'user_id' => 1, // tạm fix (sau dùng auth)
            'tour_id' => $tour->id,
            'booking_date' => now(),
            'total_price' => $total_price,
            'status' => 'pending'
        ]);

        // 6. Tạo booking_detail
        BookingDetail::create([
            'booking_id' => $booking->id,
            'quantity' => $data['quantity'],
            'price' => $tour->price
        ]);

        // 7. Trừ slot
        $tour->decrement('available_slots', $data['quantity']);

        return redirect()->back()->with('success', 'Đặt tour thành công');
    }

    public function index()
    {
        $bookings = Booking::with('tour')
            ->where('user_id', 1) // tạm
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }
    public function show($id)
    {
        $booking = Booking::with(['tour', 'bookingDetail'])
            ->where('user_id', 1) // tạm
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }
}
