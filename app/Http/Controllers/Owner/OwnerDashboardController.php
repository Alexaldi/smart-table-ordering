<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use App\Services\Reports\FinancialReportService;
use Illuminate\Http\Request;

class OwnerDashboardController extends Controller
{
    public function __invoke(Request $request, FinancialReportService $reportService)
    {
        $filters = $reportService->filtersFromRequest($request);

        return view('owner.dashboard', [
            'filters' => $filters,
            'filterQuery' => $reportService->filterQuery($filters),
            'paymentMethods' => FinancialReportService::PAYMENT_METHODS,
            'report' => $reportService->report($filters),
            'shifts' => Shift::orderBy('name')->get(),
        ]);
    }
}
