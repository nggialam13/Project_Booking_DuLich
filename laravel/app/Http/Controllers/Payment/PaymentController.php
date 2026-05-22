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

    public function create($booking_id): View
    {
        $booking = Booking::query()
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
            'method' => 'required|in:momo,vnpay',
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

        if ($validated['method'] === 'momo') {
            return redirect()->route('payment.momo', $payment->id);
        }

        return redirect()->route('payment.vnpay', $payment->id);
    }

    public function momo($id): RedirectResponse
    {
        $payment = $this->paymentForCurrentUser((int) $id);

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

    public function index(): View
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
