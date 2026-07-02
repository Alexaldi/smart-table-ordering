<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiningTable;
use App\Models\Order;
use App\Models\Shift;
use App\Models\User;
use App\Services\Reports\AdminOrderReportService;
use App\Services\Reports\FinancialReportService;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    public function index(Request $request, AdminOrderReportService $reportService)
    {
        $filters = $reportService->filtersFromRequest($request);
        $report = $reportService->report($filters);

        return view('Admin.orders.index', [
            'filters' => $filters,
            'filterQuery' => $reportService->filterQuery($filters),
            'paymentMethods' => FinancialReportService::PAYMENT_METHODS,
            'orderStatuses' => AdminOrderReportService::ORDER_STATUSES,
            'report' => $report,
            'shifts' => Shift::orderBy('name')->get(),
            'tables' => DiningTable::orderBy('table_number')->get(),
            'cashiers' => User::where('role', 'kasir')->orderBy('name')->get(),
            'activeTab' => $filters['tab'],
        ]);
    }

    public function show(Order $order)
    {
        $order->load([
            'table',
            'payment.processedBy.shift',
            'orderItems.menuItem',
            'orderItems.rejectItems.orderItem.menuItem',
            'orderItems.rejectItems.reportedBy',
            'orderItems.kitchenQueues.confirmedBy',
        ]);

        return view('Admin.orders.show', compact('order'));
    }

    public function export(Request $request, string $type, AdminOrderReportService $reportService)
    {
        abort_unless(in_array($type, ['current', 'daily', 'paid', 'reject'], true), 404);

        $filters = $reportService->filtersFromRequest($request);
        $type = $type === 'current'
            ? match ($filters['tab']) {
                'rejected' => 'reject',
                'closing' => 'daily',
                default => $filters['tab'],
            }
        : $type;

        abort_unless(in_array($type, ['all', 'daily', 'paid', 'pending', 'reject'], true), 404);

        $filename = 'admin-orders-'.$type.'-'.$filters['start_date'].'-to-'.$filters['end_date'].'.csv';

        return response()->streamDownload(function () use ($type, $filters, $reportService) {
            $handle = fopen('php://output', 'w');

            if ($type === 'daily') {
                $report = $reportService->report($filters);
                $summary = $report['financial'];
                fputcsv($handle, ['Metric', 'Value']);
                fputcsv($handle, ['Period', $filters['start_date'].' - '.$filters['end_date']]);
                fputcsv($handle, ['Total Paid Orders', $summary['total_orders']]);
                fputcsv($handle, ['Gross Revenue', $summary['gross_revenue']]);
                fputcsv($handle, ['Net Revenue', $summary['net_revenue']]);
                fputcsv($handle, ['Cash Total', $summary['cash_total']]);
                fputcsv($handle, ['Cashless Total', $summary['cashless_total']]);
                fputcsv($handle, ['Discount Total', $summary['total_discount']]);
                fputcsv($handle, ['Reject Cost', $summary['reject_cost']]);
                fputcsv($handle, ['Average Order Value', $summary['average_order_value']]);
                fputcsv($handle, ['Pending Payment Count', $report['daily_summary']['pending_payment_count']]);
                fputcsv($handle, ['Total Menu Sold', $report['daily_summary']['total_menu_sold']]);
            }

            if ($type === 'all') {
                fputcsv($handle, ['Created At', 'Order Code', 'Table', 'Payment Status', 'Order Status', 'Payment Method', 'Cashier', 'Grand Total']);
                foreach ($reportService->allOrdersForExport($filters) as $order) {
                    fputcsv($handle, [
                        optional($order->created_at)->format('Y-m-d H:i'),
                        $order->order_code,
                        $order->table?->table_number,
                        $order->payment_status,
                        $order->status,
                        $order->payment?->payment_method ?? $order->payment_method,
                        $order->payment?->processedBy?->name,
                        $order->grand_total,
                    ]);
                }
            }

            if ($type === 'paid') {
                fputcsv($handle, ['Paid At', 'Order Code', 'Table', 'Cashier', 'Payment Method', 'Subtotal', 'Discount', 'Grand Total', 'Amount Paid', 'Change']);
                foreach ($reportService->paidOrdersForExport($filters) as $order) {
                    fputcsv($handle, [
                        optional($order->payment?->paid_at)->format('Y-m-d H:i') ?? optional($order->updated_at)->format('Y-m-d H:i'),
                        $order->order_code,
                        $order->table?->table_number,
                        $order->payment?->processedBy?->name,
                        $order->payment?->payment_method ?? $order->payment_method,
                        $order->subtotal,
                        $order->discount_total,
                        $order->grand_total,
                        $order->payment?->amount_paid,
                        $order->payment?->change_amount,
                    ]);
                }
            }

            if ($type === 'pending') {
                fputcsv($handle, ['Created At', 'Order Code', 'Table', 'Payment Method', 'Grand Total', 'Status']);
                foreach ($reportService->pendingOrdersForExport($filters) as $order) {
                    fputcsv($handle, [
                        optional($order->created_at)->format('Y-m-d H:i'),
                        $order->order_code,
                        $order->table?->table_number,
                        $order->payment_method,
                        $order->grand_total,
                        $order->status,
                    ]);
                }
            }

            if ($type === 'reject') {
                fputcsv($handle, ['Time', 'Order Code', 'Menu Item', 'Qty', 'Reason', 'Reported By', 'Cost Impact']);
                foreach ($reportService->rejectsForExport($filters) as $reject) {
                    fputcsv($handle, [
                        optional($reject->created_at)->format('Y-m-d H:i'),
                        $reject->orderItem?->order?->order_code,
                        $reject->orderItem?->menuItem?->name,
                        $reject->quantity,
                        $reject->reason,
                        $reject->reportedBy?->name,
                        $reject->cost_impact,
                    ]);
                }
            }

            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
