@extends('layouts.admin')

@section('content')

<div class="container-fluid py-4">

    {{-- ===================== --}}
    {{-- HEADER --}}
    {{-- ===================== --}}
    <div class="mb-4">
        <h2 class="fw-bold text-white">📊 Dashboard Thống kê</h2>
        <p style="color:#94a3b8;">Phân tích theo ngày / tuần / tháng</p>
    </div>

    {{-- ===================== --}}
    {{-- CARDS --}}
    {{-- ===================== --}}
    <div class="row g-4 mb-4">

        {{-- USER --}}
        <div class="col-md-3">
            <div class="card border-0 h-100"
                style="border-radius:20px; background:#1e293b;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(99,102,241,0.2);">
                            <i class="fas fa-users" style="color:#6366f1;"></i>
                        </div>
                        <small style="color:#94a3b8;">Tổng User</small>
                    </div>
                    <h2 class="fw-bold text-white mb-1">{{ number_format($totalUser) }}</h2>
                    <small style="color:#22c55e;">▲ 8.2% so với tháng trước</small>
                </div>
            </div>
        </div>

        {{-- TOUR --}}
        <div class="col-md-3">
            <div class="card border-0 h-100"
                style="border-radius:20px; background:#1e293b;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(245,158,11,0.2);">
                            <i class="fas fa-map-marked-alt" style="color:#f59e0b;"></i>
                        </div>
                        <small style="color:#94a3b8;">Tour Active</small>
                    </div>
                    <h2 class="fw-bold text-white mb-1">{{ number_format($totalTourActive) }}</h2>
                    <small style="color:#22c55e;">▲ 3.2% so với tháng trước</small>
                </div>
            </div>
        </div>

        {{-- BOOKING --}}
        <div class="col-md-3">
            <div class="card border-0 h-100"
                style="border-radius:20px; background:#1e293b;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(6,182,212,0.2);">
                            <i class="fas fa-calendar-check" style="color:#06b6d4;"></i>
                        </div>
                        <small style="color:#94a3b8;">Tổng Booking</small>
                    </div>
                    <h2 class="fw-bold text-white mb-1">{{ number_format($totalBooking) }}</h2>
                    <small style="color:#22c55e;">▲ 12.7% so với tháng trước</small>
                </div>
            </div>
        </div>

        {{-- REVENUE --}}
        <div class="col-md-3">
            <div class="card border-0 h-100"
                style="border-radius:20px; background:#1e293b;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width:48px;height:48px;background:rgba(34,197,94,0.2);">
                            <i class="fas fa-dollar-sign" style="color:#22c55e;"></i>
                        </div>
                        <small style="color:#94a3b8;">Tổng Doanh Thu</small>
                    </div>
                    <h2 class="fw-bold text-white mb-1">{{ number_format($totalRevenue,0,',','.') }}đ</h2>
                    <small style="color:#22c55e;">▲ 19.6% so với tháng trước</small>
                </div>
            </div>
        </div>

    </div>

    {{-- ===================== --}}
    {{-- CHART --}}
    {{-- ===================== --}}
    <div class="card border-0 mb-4"
        style="border-radius:24px; background:#1e293b;">

        <div class="d-flex justify-content-between align-items-center p-4 flex-wrap gap-3">
            <div>
                <p class="text-uppercase small mb-1" style="color:#94a3b8; letter-spacing:2px;">
                    Banking Analytics
                </p>
                <h4 class="fw-bold text-white mb-1">Booking Statistics</h4>
                <small style="color:#64748b;">Phân tích theo ngày / tuần / tháng</small>
            </div>
            <div class="d-flex gap-2">
                <button id="btn-day" class="btn btn-primary px-4" onclick="changeChart('day')">Ngày</button>
                <button id="btn-week" class="btn px-4" onclick="changeChart('week')"
                    style="background:#1e293b; color:#94a3b8; border:1px solid #334155;">Tuần</button>
                <button id="btn-month" class="btn px-4" onclick="changeChart('month')"
                    style="background:#1e293b; color:#94a3b8; border:1px solid #334155;">Tháng</button>
            </div>
        </div>

        <div class="px-4 pb-4">
            <div style="height:350px;">
                <canvas id="bookingChart"></canvas>
            </div>
        </div>

    </div>

</div>

{{-- ===================== --}}
{{-- DATA BLADE --}}
{{-- ===================== --}}
<script>
    const chartData = document.getElementById('chartData');

    const dayLabels   = chartData.dataset.dayLabels.split(',');
    const dayData     = chartData.dataset.dayData.split(',').map(Number);
    const weekLabels  = chartData.dataset.weekLabels.split(',');
    const weekData    = chartData.dataset.weekData.split(',').map(Number);
    const monthLabels = chartData.dataset.monthLabels.split(',');
    const monthData   = chartData.dataset.monthData.split(',').map(Number);
</script>

{{-- ===================== --}}
{{-- CHART JS --}}
{{-- ===================== --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const ctx = document.getElementById('bookingChart');
        if (!ctx) return;

        let bookingChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: dayLabels,
                datasets: [{
                    label: 'Số Booking',
                    data: dayData,
                    backgroundColor: 'rgba(99,102,241,0.85)',
                    borderColor: '#6366f1',
                    borderWidth: 0,
                    borderRadius: 8,
                    borderSkipped: false,
                    barThickness: 28
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        labels: { color: '#cbd5e1' }
                    }
                },
                scales: {
                    x: {
                        ticks: { color: '#64748b' },
                        grid:  { color: 'rgba(255,255,255,0.04)' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: { color: '#64748b' },
                        grid:  { color: 'rgba(255,255,255,0.04)' }
                    }
                }
            }
        });

        window.changeChart = function(type) {

            // Reset tất cả button
            ['day','week','month'].forEach(t => {
                const btn = document.getElementById('btn-' + t);
                btn.className = 'btn px-4';
                btn.style = 'background:#1e293b; color:#94a3b8; border:1px solid #334155;';
            });

            if (type === 'day') {
                bookingChart.data.labels = dayLabels;
                bookingChart.data.datasets[0].data = dayData;
                bookingChart.data.datasets[0].backgroundColor = 'rgba(99,102,241,0.85)';
                document.getElementById('btn-day').className = 'btn btn-primary px-4';
                document.getElementById('btn-day').style = '';
            }
            if (type === 'week') {
                bookingChart.data.labels = weekLabels;
                bookingChart.data.datasets[0].data = weekData;
                bookingChart.data.datasets[0].backgroundColor = 'rgba(245,158,11,0.85)';
                document.getElementById('btn-week').className = 'btn btn-warning px-4';
                document.getElementById('btn-week').style = '';
            }
            if (type === 'month') {
                bookingChart.data.labels = monthLabels;
                bookingChart.data.datasets[0].data = monthData;
                bookingChart.data.datasets[0].backgroundColor = 'rgba(34,197,94,0.85)';
                document.getElementById('btn-month').className = 'btn btn-success px-4';
                document.getElementById('btn-month').style = '';
            }

            bookingChart.update();
        };

    });
</script>

@endsection