<?php

namespace App\Http\Controllers\Booking;

use Illuminate\Http\Request;
use App\Services\BookingService;

use App\Http\Controllers\Controller;

class BookingController extends Controller
{
    protected $bookingService;

    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
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
      public function create($tourId)
{
    $tour = \App\Models\Tour::findOrFail($tourId);

    return view('bookings.create', compact('tour'));
}
}