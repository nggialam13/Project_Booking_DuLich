<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\BookingService;
use App\Models\Tour;

class BookingController extends Controller
{
    public function __construct(private BookingService $bookingService)
    {
    }


    public function create($tourId)
    {
        $tour = Tour::findOrFail($tourId);

        return view('bookings.create', compact('tour'));
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $userId = Auth::id();

        if (!$userId) {
            return redirect('/login')->with('error', 'Vui lòng đăng nhập');
        }

        try {
            $booking = $this->bookingService->createBooking(
                $userId,
                (int) $data['tour_id'],
                (int) $data['quantity']
            );
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('bookings.show', $booking->id)
            ->with('success', 'Đặt tour thành công');
    }

    public function index()
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect('/login');
        }

        // Read/list query is delegated to service for cleaner controller.
        $bookings = $this->bookingService->getUserBookingsPaginated($userId);

        return view('bookings.index', compact('bookings'));
    }
    public function show($id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect('/login');
        }

        // Detail query is delegated to service for cleaner controller.
        $booking = $this->bookingService->getUserBookingDetail($userId, (int) $id);

        return view('bookings.show', compact('booking'));
    }


    // Hủy booking
    public function cancel($id)
    {
        $userId = Auth::id();

        if (!$userId) {
            return redirect('/login');
        }
        try {
            $this->bookingService->cancelByUser((int) $id, $userId);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Hủy booking thành công');
    }
    // Admin xem tất cả booking

  public function adminIndex(Request $request)
{
    $status = $request->query('status');
    $keyword = $request->query('keyword');

    $bookings = $this->bookingService
        ->getAdminBookingsPaginated($status, $keyword);

    return view('bookings.admin-index', compact('bookings', 'status', 'keyword'));
}


    // Admin xác nhận booking
    public function confirm($id)
    {
        try {
            $this->bookingService->confirm((int) $id);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Xác nhận booking thành công');
    }

    // Admin hủy booking
    public function adminCancel($id)
    {
        try {
            $this->bookingService->cancelByAdmin((int) $id);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Admin đã hủy booking');
    }
}
