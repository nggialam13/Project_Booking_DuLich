<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        // 1. Validate (bắt buộc theo quy ước team)
        $data = $request->validate([
            'tour_id' => 'required|exists:tours,id',
            'quantity' => 'required|integer|min:1'
        ]);

        // 2. user_id hợp lý:
        // - Nếu đã đăng nhập: dùng Auth::id()
        // - Nếu chưa có chức năng đăng nhập (đang test local): dùng BOOKING_TEST_USER_ID trong .env
        // LƯU Ý: chỉ dùng để test, khi merge auth của người 1 thì nên bỏ fallback này.
        $userId = $this->getUserIdForBookingTest();
        if (!$userId) {
            return back()->with('error', 'Vui lòng đăng nhập để đặt tour');
        }

        // 3. Transaction (QUAN TRỌNG NHẤT)
        // Mục tiêu: chống overbooking khi nhiều người đặt cùng lúc
        $result = DB::transaction(function () use ($data, $userId) {
            // Lock tour để đảm bảo slot không bị trừ sai khi concurrent
            $tour = Tour::where('id', $data['tour_id'])->lockForUpdate()->firstOrFail();

            // Check slot sau khi lock
            if ($data['quantity'] > $tour->available_slots) {
                return ['ok' => false, 'message' => 'Không đủ chỗ'];
            }

            // Tính tổng tiền
            $totalPrice = $tour->price * $data['quantity'];

            // Tạo booking
            $booking = Booking::create([
                'user_id' => $userId,
                'tour_id' => $tour->id,
                'booking_date' => now(),
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            // Tạo booking_detail
            BookingDetail::create([
                'booking_id' => $booking->id,
                'quantity' => $data['quantity'],
                'price' => $tour->price
            ]);

            // Trừ slot (đã lock nên an toàn)
            $tour->decrement('available_slots', $data['quantity']);

            return ['ok' => true, 'booking_id' => $booking->id];
        });

        if (!$result['ok']) {
            return back()->with('error', $result['message']);
        }

        return redirect()->route('bookings.show', $result['booking_id'])
            ->with('success', 'Đặt tour thành công');
    }

    public function index()
    {
        $userId = $this->getUserIdForBookingTest();
        if (!$userId) {
            return redirect('/')->with('error', 'Vui lòng đăng nhập để xem booking');
        }

        $bookings = Booking::with('tour')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        return view('bookings.index', compact('bookings'));
    }
    public function show($id)
    {
        $userId = $this->getUserIdForBookingTest();
        if (!$userId) {
            return redirect('/')->with('error', 'Vui lòng đăng nhập để xem booking');
        }

        $booking = Booking::with(['tour', 'bookingDetail'])
            ->where('user_id', $userId)
            ->findOrFail($id);

        return view('bookings.show', compact('booking'));
    }


    // Hủy booking
    public function cancel($id)
    {
        $userId = $this->getUserIdForBookingTest();
        $booking = Booking::with(['bookingDetail', 'tour'])
            ->where('user_id', $userId)
            ->findOrFail($id);

        return $this->cancelBooking($booking, 'Hủy booking thành công');
    }
    // Admin xem tất cả booking

   public function adminIndex(Request $request)
{
    $query = Booking::with(['tour', 'user'])->latest();

    $status = $request->query('status');

    if (in_array($status, ['pending', 'confirmed', 'cancelled'], true)) {
        $query->where('status', $status);
    }

    $bookings = $query->paginate(10)->withQueryString();

    return view('bookings.admin.index', compact('bookings', 'status'));
}


    // Admin xác nhận booking
    public function confirm($id)
    {
        $booking = Booking::findOrFail($id);

        // Không cho confirm nếu không phải pending
        if ($booking->status != 'pending') {
            return back()->with('error', 'Không thể xác nhận booking này');
        }

        $booking->update([
            'status' => 'confirmed'
        ]);

        return back()->with('success', 'Xác nhận booking thành công');
    }

    // Admin hủy booking
  public function adminCancel($id)
{
  $booking = Booking::with(['bookingDetail', 'tour'])->findOrFail($id);
    return $this->cancelBooking($booking, 'Admin đã hủy booking');
}

    /**
     * Cancel chuẩn:
     * - Chỉ hủy khi chưa cancelled
     * - Restore slot đúng quantity (null-safe)
     * - Transaction + lock tour để không lệch slot
     */
    private function cancelBooking(Booking $booking, string $successMessage)
    {
        if ($booking->status === 'cancelled') {
            return back()->with('error', 'Booking đã bị hủy');
        }

        DB::transaction(function () use ($booking) {
            // lock booking row để tránh 2 request cancel cùng lúc
            $booking = Booking::where('id', $booking->id)->lockForUpdate()->firstOrFail();
            if ($booking->status === 'cancelled') {
                return;
            }

            // Lấy quantity an toàn
          $booking->load('bookingDetail');
$quantity = (int) optional($booking->bookingDetail)->quantity;

            // Update status
            $booking->update(['status' => 'cancelled']);

            // Restore slot
            if ($quantity > 0) {
                $tour = Tour::where('id', $booking->tour_id)->lockForUpdate()->first();
                if ($tour) {
                    $tour->increment('available_slots', $quantity);
                }
            }
        });

        return back()->with('success', $successMessage);
    }

    // Helper lấy user_id để test nhanh bằng .env (không cần login UI)
    private function getUserIdForBookingTest(): ?int
    {
        $authId = Auth::id();
        if ($authId) {
            return (int) $authId;
        }

        $testId = (int) env('BOOKING_TEST_USER_ID', 0);
        return $testId > 0 ? $testId : null;
    }
}
