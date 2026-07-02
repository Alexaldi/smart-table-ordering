<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Services\Reports\FinancialReportService;
use Illuminate\Http\Request;

class FinancialReportController extends Controller
{
    public function __invoke(Request $request, FinancialReportService $reportService)
    {
        $filters = $reportService->filtersFromRequest($request);
        $report = $reportService->report($filters);

        return view('Admin.reports.financial', [
            'filters' => $filters,
            'filterQuery' => $reportService->filterQuery($filters),
            'paymentMethods' => FinancialReportService::PAYMENT_METHODS,
            'report' => $report,
            'shifts' => Shift::orderBy('name')->get(),
        ]);
    }

    public function realtime(Request $request, FinancialReportService $reportService)
    {
        $filters = $reportService->filtersFromRequest($request);
        $report = $reportService->report($filters);

        return response()->json([
            'stats_html' => view('Admin.reports.partials.stats', compact('report'))->render(),
            'orders_html' => view('Admin.reports.partials.orders', compact('report'))->render(),
            'reject_html' => view('Admin.reports.partials.reject', compact('report'))->render(),
            'breakdown_html' => view('Admin.reports.partials.breakdown', compact('report'))->render(),
        ]);
    }
}
