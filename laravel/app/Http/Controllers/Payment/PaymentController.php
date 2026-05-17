<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    private function paymentForCurrentUser(int $id): Payment
    {
        return Payment::query()
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($id);
    }

    private function bookingForCurrentUser(int $bookingId): Booking
    {
        return Booking::query()
            ->where('user_id', auth()->id())
            ->with(['tour'])
            ->findOrFail($booking_id);

        $amount = (int) $booking->total_price;

        return view('payments.create', compact('booking', 'amount'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:cash,momo,vnpay',
        ]);

        $booking = Booking::query()
            ->where('user_id', auth()->id())
            ->findOrFail($validated['booking_id']);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => (int) round($validated['amount']),
            'payment_method' => $validated['method'],
            'status' => 'pending',
        ]);

        if ($validated['method'] === 'cash') {
            return redirect()
                ->route('payment.show', $payment->id)
                ->with('success', 'Đã ghi nhận yêu cầu thanh toán tiền mặt. Vui lòng thanh toán tại quầy và chờ quản trị viên xác nhận.');
        }

        if ($validated['method'] === 'momo') {
            return redirect()->route('payment.momo', $payment->id);
        }

        return redirect()->route('payment.vnpay', $payment->id);
    }

    public function momo($id): RedirectResponse
    {
        $payment = $this->paymentForCurrentUser((int) $id);

        if ($payment->status === 'paid') {
            return redirect()->route('payment.success', $id);
        }

        $payment->update([
            'status' => 'paid',
        ]);

        $booking = Booking::find($payment->booking_id);
        if ($booking) {
            $booking->status = 'confirmed';
            $booking->save();
        }

        return redirect()->route('payment.success', $id);
    }

    public function vnpay($id): RedirectResponse
    {
        $payment = $this->paymentForCurrentUser((int) $id);

        if ($payment->status === 'paid') {
            return redirect()->route('payment.success', $id);
        }

        $payment->update([
            'status' => 'paid',
        ]);

        $booking = Booking::find($payment->booking_id);
        if ($booking) {
            $booking->status = 'confirmed';
            $booking->save();
        }

        return redirect()->route('payment.success', $id);
    }

    public function success($id): View
    {
        $payment = $this->paymentForCurrentUser((int) $id);
        $payment->load(['booking.tour']);

        return view('payments.success', compact('payment'));
    }

    public function index(Request $request): View
    {
        $payments = Payment::query()
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['booking.tour'])
            ->latest()
            ->paginate(12);

        return view('payments.index', compact('payments'));
    }

    public function show($id): View
    {
        $payment = $this->paymentForCurrentUser((int) $id);
        $payment->load(['booking.tour']);

        return view('payments.show', compact('payment'));
    }
}