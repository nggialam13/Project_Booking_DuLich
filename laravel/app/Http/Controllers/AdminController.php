<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function payments(Request $request): View
    {
        $statusFilter = null;
        if ($request->filled('status')) {
            $s = $request->string('status')->toString();
            if (in_array($s, ['pending', 'paid'], true)) {
                $statusFilter = $s;
            }
        }

        $query = Payment::with(['booking.tour', 'booking.user'])->latest();
        if ($statusFilter !== null) {
            $query->where('status', $statusFilter);
        }

        $payments = $query->paginate(15);
        if ($statusFilter !== null) {
            $payments->appends(['status' => $statusFilter]);
        }

        $stats = [
            'total' => Payment::count(),
            'paid_count' => Payment::where('status', 'paid')->count(),
            'revenue' => (int) Payment::where('status', 'paid')->sum('amount'),
        ];

        return view('admin.payments.index', compact('payments', 'stats', 'statusFilter'));
    }

    public function createPayment(): View
    {
        $bookings = Booking::with(['tour', 'user'])
            ->orderByDesc('id')
            ->get();

        return view('admin.payments.create', compact('bookings'));
    }

    public function storePayment(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
            'amount' => 'required|integer|min:0',
            'payment_method' => 'required|in:cash,momo,vnpay',
        ]);

        $booking = Booking::findOrFail($validated['booking_id']);

        if ($booking->status === 'cancelled') {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Không thể tạo thanh toán cho booking đã hủy.');
        }

        if ($validated['payment_method'] === 'cash') {
            Payment::create([
                'booking_id' => $booking->id,
                'amount' => $validated['amount'],
                'payment_method' => 'cash',
                'status' => 'paid',
            ]);

            $booking->status = 'confirmed';
            $booking->save();

            return redirect()
                ->route('admin.payments.index')
                ->with('success', 'Đã tạo thanh toán tiền mặt và xác nhận booking.');
        }

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'amount' => $validated['amount'],
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        if ($validated['payment_method'] === 'momo') {
            return redirect()->route('payment.momo', $payment->id);
        }

        return redirect()->route('payment.vnpay', $payment->id);
    }

    public function showPayment(Payment $payment): View
    {
        $payment->load(['booking.tour', 'booking.user']);

        return view('admin.payments.show', compact('payment'));
    }

    public function updatePaymentStatus(Request $request, Payment $payment): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,paid',
        ]);

        $payment->status = $validated['status'];
        $payment->save();

        $booking = $payment->booking;
        if ($booking && $booking->status !== 'cancelled') {
            if ($validated['status'] === 'paid') {
                $booking->update(['status' => 'confirmed']);
            } else {
                $booking->update(['status' => 'pending']);
            }
        }

        return redirect()
            ->route('admin.payments.show', $payment)
            ->with('success', 'Đã cập nhật trạng thái thanh toán.');
    }
}
