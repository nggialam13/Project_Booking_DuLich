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
            ->with('booking')
            ->whereHas('booking', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->findOrFail($id);
    }

    private function bookingForCurrentUser(int $bookingId): Booking
    {
        return Booking::query()
            ->where('user_id', auth()->id())
            ->with(['tour', 'payments'])
            ->findOrFail($bookingId);
    }

    private function redirectIfCannotPay(Booking $booking): ?RedirectResponse
    {
        if ($booking->status === 'cancelled') {
            return redirect()
                ->route('bookings.show', $booking->id)
                ->with('error', 'Booking đã hủy, không thể thanh toán.');
        }

        $paid = $booking->payments->firstWhere('status', 'paid');
        if ($paid) {
            return redirect()
                ->route('payment.show', $paid->id)
                ->with('info', 'Booking này đã được thanh toán.');
        }

        return null;
    }

    private function redirectPendingPayment(Payment $pending): RedirectResponse
    {
        if ($pending->payment_method === 'momo') {
            return redirect()->route('payment.momo', $pending->id);
        }
        if ($pending->payment_method === 'vnpay') {
            return redirect()->route('payment.vnpay', $pending->id);
        }

        return redirect()
            ->route('payment.show', $pending->id)
            ->with('info', 'Bạn đã có giao dịch đang chờ xử lý.');
    }

    private function confirmBookingIfPaid(Payment $payment): void
    {
        $booking = $payment->booking ?? Booking::find($payment->booking_id);
        if ($booking && $booking->status !== 'cancelled') {
            $booking->update(['status' => 'confirmed']);
        }
    }

    public function create($booking_id): View|RedirectResponse
    {
        $booking = $this->bookingForCurrentUser((int) $booking_id);

        if ($redirect = $this->redirectIfCannotPay($booking)) {
            return $redirect;
        }

        $pending = $booking->payments->where('status', 'pending')->sortByDesc('id')->first();
        if ($pending) {
            return $this->redirectPendingPayment($pending);
        }

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

        $booking = $this->bookingForCurrentUser((int) $validated['booking_id']);

        if ($redirect = $this->redirectIfCannotPay($booking)) {
            return $redirect;
        }

        $pending = $booking->payments->where('status', 'pending')->sortByDesc('id')->first();
        if ($pending) {
            return $this->redirectPendingPayment($pending);
        }

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

        if ($payment->payment_method !== 'momo') {
            return redirect()->route('payment.show', $payment->id)->with('error', 'Giao dịch không dùng phương thức MoMo.');
        }

        $booking = $payment->booking;
        if ($booking && $booking->status === 'cancelled') {
            return redirect()->route('bookings.show', $booking->id)->with('error', 'Booking đã hủy, không thể thanh toán.');
        }

        $payment->update(['status' => 'paid']);
        $this->confirmBookingIfPaid($payment);

        return redirect()->route('payment.success', $id);
    }

    public function vnpay($id): RedirectResponse
    {
        $payment = $this->paymentForCurrentUser((int) $id);

        if ($payment->status === 'paid') {
            return redirect()->route('payment.success', $id);
        }

        if ($payment->payment_method !== 'vnpay') {
            return redirect()->route('payment.show', $payment->id)->with('error', 'Giao dịch không dùng phương thức VNPay.');
        }

        $booking = $payment->booking;
        if ($booking && $booking->status === 'cancelled') {
            return redirect()->route('bookings.show', $booking->id)->with('error', 'Booking đã hủy, không thể thanh toán.');
        }

        $payment->update(['status' => 'paid']);
        $this->confirmBookingIfPaid($payment);

        return redirect()->route('payment.success', $id);
    }

    public function success($id): View|RedirectResponse
    {
        $payment = $this->paymentForCurrentUser((int) $id);
        $payment->load(['booking.tour']);

        if ($payment->status !== 'paid') {
            return redirect()->route('payment.show', $payment->id);
        }

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
