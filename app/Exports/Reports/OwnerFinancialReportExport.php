<?php

namespace App\Exports\Reports;

use App\Services\Reports\FinancialReportService;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class OwnerFinancialReportExport implements WithMultipleSheets
{
    public function __construct(
        private readonly FinancialReportService $reportService,
        private readonly array $filters
    ) {}

    public function sheets(): array
    {
        $report = $this->reportService->report($this->filters);
        $paidOrders = $this->reportService->paidOrdersForExport($this->filters);
        $rejects = $this->reportService->rejectsForExport($this->filters);
        $bestSellingMenus = $this->reportService->bestSellingMenus($this->filters, 25);

        return [
            new ArrayReportSheet('Summary', ['Metric', 'Value'], [
                ['Period', $this->filters['start_date'].' - '.$this->filters['end_date']],
                ['Gross Revenue', $report['gross_revenue']],
                ['Net Revenue Estimate', $report['net_revenue']],
                ['Total Orders', $report['total_orders']],
                ['Average Order Value', $report['average_order_value']],
                ['Discount Given', $report['total_discount']],
                ['Reject Cost', $report['reject_cost']],
                ['Cash Revenue', $report['cash_total']],
                ['Cashless Revenue', $report['cashless_total']],
            ]),

            new ArrayReportSheet('Paid Orders', [
                'Paid At',
                'Order Code',
                'Table',
                'Payment Method',
                'Cashier',
                'Subtotal',
                'Discount',
                'Grand Total',
            ], $paidOrders->map(fn ($order) => [
                optional($order->payment?->paid_at)->format('Y-m-d H:i') ?? optional($order->updated_at)->format('Y-m-d H:i'),
                $order->order_code,
                $order->table?->table_number ?? '-',
                strtoupper($order->payment?->payment_method ?? $order->payment_method ?? '-'),
                $order->payment?->processedBy?->name ?? '-',
                (float) $order->subtotal,
                (float) $order->discount_total,
                (float) $order->grand_total,
            ])->all()),

            new ArrayReportSheet('Reject Summary', [
                'Reported At',
                'Order Code',
                'Menu',
                'Qty',
                'Action',
                'Reason',
                'Reported By',
                'Cost Impact',
            ], $rejects->map(fn ($reject) => [
                optional($reject->created_at)->format('Y-m-d H:i'),
                $reject->orderItem?->order?->order_code ?? '-',
                $reject->orderItem?->menuItem?->name ?? '-',
                (int) $reject->quantity,
                strtoupper($reject->action),
                $reject->reason,
                $reject->reportedBy?->name ?? '-',
                (float) $reject->cost_impact,
            ])->all()),

            new ArrayReportSheet('Best Selling Menu', [
                'Menu',
                'Quantity Sold',
                'Revenue',
                'Discount',
            ], $bestSellingMenus->map(fn ($menu) => [
                $menu->menu_name,
                (int) $menu->quantity_sold,
                (float) $menu->revenue,
                (float) $menu->discount_total,
            ])->all()),
        ];
    }
}
