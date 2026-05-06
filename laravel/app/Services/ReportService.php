<?php
namespace App\Services;

use App\Models\Booking;
use App\Models\User;
use App\Models\Tour;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getReportData($filters = [])
    {
        $from = $filters['from'] ?? null;
        $to   = $filters['to'] ?? null;
        $sort = $filters['sort'] ?? 'desc';

        // BASE QUERY
        $query = Booking::with(['user', 'tour']);

        if ($from) {
            $query->whereDate('created_at', '>=', $from);
        }

        if ($to) {
            $query->whereDate('created_at', '<=', $to);
        }

        // BOOKING LIST (PAGINATION)
        $bookings = (clone $query)
            ->orderBy('created_at', $sort)
            ->paginate(10)
            ->withQueryString();

        // DASHBOARD STATS
        $totalBooking = (clone $query)->count();

        $totalRevenue = (clone $query)
            ->where('status', 'confirmed')
            ->sum('total_price');

        $totalUser = User::count();

        $totalTourActive = Tour::where('status', 'active')->count();

        // REVENUE BY MONTH
        $revenueByMonth = Booking::select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_price) as revenue')
            )
            ->where('status', 'confirmed')
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // BOOKING BY DAY
        $bookingByDay = Booking::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // TOP TOUR
        $topTours = Booking::select('tour_id', DB::raw('COUNT(*) as total'))
            ->groupBy('tour_id')
            ->orderByDesc('total')
            ->with('tour')
            ->limit(5)
            ->get();

        return [
            'bookings' => $bookings,
            'totalBooking' => $totalBooking,
            'totalRevenue' => $totalRevenue,
            'totalUser' => $totalUser,
            'totalTourActive' => $totalTourActive,
            'revenueByMonth' => $revenueByMonth,
            'bookingByDay' => $bookingByDay,
            'topTours' => $topTours,
        ];
    }
}