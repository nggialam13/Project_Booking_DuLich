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

    public function index(Request $request): View
    {
        $statusFilter = null;
        if ($request->filled('status')) {
            $s = $request->string('status')->toString();
            if (in_array($s, ['pending', 'paid'], true)) {
                $statusFilter = $s;
            }
        }

        $methodFilter = null;
        if ($request->filled('method')) {
            $m = $request->string('method')->toString();
            if (in_array($m, ['cash', 'momo', 'vnpay'], true)) {
                $methodFilter = $m;
            }
        }

        $query = Payment::query()
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['booking.tour'])
            ->latest();

        if ($statusFilter !== null) {
            $query->where('status', $statusFilter);
        }
        if ($methodFilter !== null) {
            $query->where('payment_method', $methodFilter);
        }

        $payments = $query->paginate(12);
        $filterQuery = array_filter([
            'status' => $statusFilter,
            'method' => $methodFilter,
        ], fn ($v) => $v !== null);
        if ($filterQuery !== []) {
            $payments->appends($filterQuery);
        }

        return view('payments.index', compact('payments', 'statusFilter', 'methodFilter'));
    }

    public function show($id): View
    {
        $payment = $this->paymentForCurrentUser((int) $id);
        $payment->load(['booking.tour']);

        return view('payments.show', compact('payment'));
    }
}
