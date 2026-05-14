<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'from' => 'nullable|date',
            'to'   => 'nullable|date',
            'status' => 'nullable',
            'keyword' => 'nullable'
        ]);

        $reportService = app(ReportService::class);

        $data = $reportService->getReportData($filters);

        return view('admin.report', $data);
    }
}
