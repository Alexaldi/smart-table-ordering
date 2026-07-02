<?php

namespace App\Http\Controllers\Owner;

use App\Exports\Reports\OwnerFinancialReportExport;
use App\Http\Controllers\Controller;
use App\Services\Reports\FinancialReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class OwnerReportExportController extends Controller
{
    public function excel(Request $request, FinancialReportService $reportService)
    {
        $filters = $reportService->filtersFromRequest($request);
        $filename = 'owner-report-'.$filters['start_date'].'-to-'.$filters['end_date'].'.xlsx';

        return Excel::download(new OwnerFinancialReportExport($reportService, $filters), $filename);
    }

    public function pdf(Request $request, FinancialReportService $reportService)
    {
        $filters = $reportService->filtersFromRequest($request);
        $data = [
            'filters' => $filters,
            'report' => $reportService->report($filters),
            'paidOrders' => $reportService->paidOrdersForExport($filters),
            'rejects' => $reportService->rejectsForExport($filters),
            'bestSellingMenus' => $reportService->bestSellingMenus($filters, 25),
            'isPdfFallback' => ! class_exists(Pdf::class),
        ];

        if (class_exists(Pdf::class)) {
            return Pdf::loadView('owner.reports.pdf', $data)
                ->setPaper('a4')
                ->download('owner-report-'.$filters['start_date'].'-to-'.$filters['end_date'].'.pdf');
        }

        // PDF package is not installed in this project yet; this printable HTML is ready for browser "Save as PDF".
        return response()
            ->view('owner.reports.pdf', $data)
            ->header('Content-Type', 'text/html; charset=UTF-8');
    }
}
