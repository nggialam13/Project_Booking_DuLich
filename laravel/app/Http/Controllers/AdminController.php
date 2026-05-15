<?php

namespace App\Http\Controllers;

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
