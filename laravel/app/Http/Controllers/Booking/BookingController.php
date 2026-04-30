<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Services\BookingService;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    public function adminIndex()
    {
        $bookings = \App\Models\Booking::with('tour', 'bookingDetail', 'user')
            ->latest()
            ->paginate(10);
        return view('bookings.admin-index', compact('bookings'));
    }

    public function show($id)
    {
        $booking = \App\Models\Booking::with(['tour', 'bookingDetail'])
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'quantity' => 'required|integer|min:1'
        ]);

        try {
            $booking = $this->bookingService->create(
                auth()->id(),
                $data['tour_id'],
                $data['quantity']
            );

            return redirect('/payments')
                ->with('success', 'Đặt tour thành công');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
    // Hiển thị form đặt tour
    public function create($tourId)
    {
        $tour = \App\Models\Tour::findOrFail($tourId);

        return view('bookings.create', compact('tour'));
    }
    // Hủy booking
    public function cancel($id)
    {
        try {
            $this->bookingService->cancel($id);

            return back()->with('success', 'Hủy booking thành công');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirm($id)
    {
        try {
            $this->bookingService->confirm($id);

            return back()->with('success', 'Xác nhận booking thành công');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}