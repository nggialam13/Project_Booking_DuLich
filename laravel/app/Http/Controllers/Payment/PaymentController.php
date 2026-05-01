<?php

namespace App\Http\Controllers\Payment;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Booking;

class PaymentController extends Controller
{
    public function create($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        $amount = $booking->total_price;

        return view('payments.create', compact('booking', 'amount'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|numeric|min:0',
            'method' => 'required|in:momo,vnpay',
        ]);

        $payment = Payment::create([
            'booking_id' => $validated['booking_id'],
            'amount' => $validated['amount'],
            'payment_method' => $validated['method'],
            'status' => 'pending',
        ]);

        if ($validated['method'] === 'momo') {
            return redirect()->route('payment.momo', $payment->id);
        }

        return redirect()->route('payment.vnpay', $payment->id);
    }

    public function momo($id)
    {
        $payment = Payment::findOrFail($id);

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

    public function vnpay($id)
    {
        $payment = Payment::findOrFail($id);

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

    public function success($id)
    {
        $payment = Payment::findOrFail($id);
        return view('payments.success', compact('payment'));
    }

    public function index()
    {
        $payments = Payment::latest()->get();
        return view('payments.index', compact('payments'));
    }

    public function show($id)
    {
        $payment = Payment::findOrFail($id);
        return view('payments.show', compact('payment'));
    }
}