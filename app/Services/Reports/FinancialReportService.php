<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\RejectItem;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class FinancialReportService
{
    public const PAYMENT_METHODS = ['cash', 'qris', 'transfer', 'other'];

    public function filtersFromRequest(Request $request): array
    {
        $todayStart = now()->startOfDay();
        $todayEnd = now()->endOfDay();

        $start = $request->filled('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : $todayStart->copy();

        $end = $request->filled('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : $todayEnd->copy();

        if ($start->greaterThan($todayEnd)) {
            $start = $todayStart->copy();
        }

        if ($end->greaterThan($todayEnd)) {
            $end = $todayEnd->copy();
        }

        if ($start->greaterThan($end)) {
            [$start, $end] = [$end->copy()->startOfDay(), $start->copy()->endOfDay()];
        }

        $paymentMethod = $request->input('payment_method');

        return [
            'start_at' => $start,
            'end_at' => $end,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'max_date' => now()->toDateString(),
            'shift_id' => $request->filled('shift_id') ? (int) $request->input('shift_id') : null,
            'payment_method' => in_array($paymentMethod, self::PAYMENT_METHODS, true) ? $paymentMethod : null,
        ];
    }

    public function filterQuery(array $filters): array
    {
        return array_filter([
            'start_date' => $filters['start_date'] ?? null,
            'end_date' => $filters['end_date'] ?? null,
            'shift_id' => $filters['shift_id'] ?? null,
            'payment_method' => $filters['payment_method'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');
    }

    public function report(array $filters): array
    {
        $paidOrdersQuery = $this->basePaidOrdersQuery($filters);

        $grossRevenue = (float) (clone $paidOrdersQuery)->sum('orders.subtotal');
        $totalDiscount = (float) (clone $paidOrdersQuery)->sum('orders.discount_total');
        $totalOrders = (int) (clone $paidOrdersQuery)->count('orders.id');
        $averageOrderValue = $totalOrders > 0
            ? (float) (clone $paidOrdersQuery)->avg('orders.grand_total')
            : 0.0;

        $cashTotal = (float) (clone $paidOrdersQuery)
            ->whereRaw($this->paymentMethodExpression().' = ?', ['cash'])
            ->sum('orders.grand_total');

        $cashlessTotal = (float) (clone $paidOrdersQuery)
            ->whereRaw($this->paymentMethodExpression().' <> ?', ['cash'])
            ->sum('orders.grand_total');

        $rejectCost = (float) $this->baseRejectQuery($filters)->sum('cost_impact');

        $recentPaidOrders = $this->recentPaidOrders($filters);
        $recentRejectSummary = $this->recentRejectSummary($filters);

        return [
            'gross_revenue' => $grossRevenue,
            'total_discount' => $totalDiscount,
            'reject_cost' => $rejectCost,
            'net_revenue' => max(0, $grossRevenue - $totalDiscount - $rejectCost),
            'total_orders' => $totalOrders,
            'average_order_value' => $averageOrderValue,
            'cash_total' => $cashTotal,
            'cashless_total' => $cashlessTotal,
            'payment_breakdown' => $this->paymentBreakdown($filters),
            'revenue_trend' => $this->revenueTrend($filters),
            'best_selling_menus' => $this->bestSellingMenus($filters),
            'recent_paid_orders' => $recentPaidOrders,
            'paid_orders' => $recentPaidOrders,
            'reject_summary' => $recentRejectSummary,
            'reject_items' => $recentRejectSummary,
            'shift_performance' => $this->shiftPerformance($filters),
        ];
    }

    public function paidOrdersForExport(array $filters): Collection
    {
        return (clone $this->basePaidOrdersQuery($filters))
            ->select('orders.*')
            ->with(['table', 'payment.processedBy.shift', 'orderItems.menuItem'])
            ->orderByDesc(DB::raw($this->paidAtExpression()))
            ->get();
    }

    public function rejectsForExport(array $filters): Collection
    {
        return $this->baseRejectQuery($filters)
            ->with(['orderItem.menuItem', 'orderItem.order.table', 'reportedBy.shift'])
            ->latest('reject_items.created_at')
            ->get();
    }

    public function bestSellingMenus(array $filters, int $limit = 8): Collection
    {
        $query = OrderItem::query()
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('users as payment_users', 'payment_users.id', '=', 'payments.processed_by')
            ->join('menu_items', 'menu_items.id', '=', 'order_items.menu_item_id');

        $this->applyPaidOrderFilters($query, $filters);

        return $query
            ->selectRaw('menu_items.id as menu_item_id')
            ->selectRaw('menu_items.name as menu_name')
            ->selectRaw('SUM(order_items.quantity) as quantity_sold')
            ->selectRaw('SUM(order_items.subtotal) as revenue')
            ->selectRaw('SUM(order_items.discount_amount * order_items.quantity) as discount_total')
            ->groupBy('menu_items.id', 'menu_items.name')
            ->orderByDesc('quantity_sold')
            ->limit($limit)
            ->get();
    }

    private function recentPaidOrders(array $filters): Collection
    {
        return (clone $this->basePaidOrdersQuery($filters))
            ->select('orders.*')
            ->with(['table', 'payment.processedBy.shift', 'orderItems.menuItem'])
            ->orderByDesc(DB::raw($this->paidAtExpression()))
            ->limit(15)
            ->get();
    }

    private function recentRejectSummary(array $filters): Collection
    {
        return $this->baseRejectQuery($filters)
            ->with(['orderItem.menuItem', 'orderItem.order.table', 'reportedBy.shift'])
            ->latest('reject_items.created_at')
            ->limit(15)
            ->get();
    }

    private function paymentBreakdown(array $filters): Collection
    {
        return (clone $this->basePaidOrdersQuery($filters))
            ->selectRaw($this->paymentMethodExpression("'unknown'").' as payment_method')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(orders.grand_total) as total_revenue')
            ->groupBy(DB::raw($this->paymentMethodExpression("'unknown'")))
            ->orderByDesc('total_revenue')
            ->get();
    }

    private function revenueTrend(array $filters): array
    {
        $orders = (clone $this->basePaidOrdersQuery($filters))
            ->selectRaw('orders.id')
            ->selectRaw('orders.subtotal')
            ->selectRaw('orders.discount_total')
            ->selectRaw('orders.grand_total')
            ->selectRaw($this->paidAtExpression().' as paid_at_report')
            ->get();

        $dailyValues = $orders
            ->groupBy(fn ($order) => Carbon::parse($order->paid_at_report)->toDateString())
            ->map(fn ($items) => (float) $items->sum('grand_total'));

        $daily = collect();
        foreach (CarbonPeriod::create($filters['start_at']->copy()->startOfDay(), $filters['end_at']->copy()->startOfDay()) as $date) {
            $key = $date->toDateString();
            $daily->push([
                'label' => $date->format('d M'),
                'date' => $key,
                'revenue' => (float) ($dailyValues[$key] ?? 0),
            ]);
        }

        $weekly = $orders
            ->groupBy(fn ($order) => Carbon::parse($order->paid_at_report)->format('o-\WW'))
            ->map(fn ($items, $week) => [
                'label' => $week,
                'revenue' => (float) $items->sum('grand_total'),
            ])
            ->values();

        $monthly = $orders
            ->groupBy(fn ($order) => Carbon::parse($order->paid_at_report)->format('Y-m'))
            ->map(fn ($items, $month) => [
                'label' => Carbon::createFromFormat('Y-m', $month)->format('M Y'),
                'revenue' => (float) $items->sum('grand_total'),
            ])
            ->values();

        return [
            'daily' => $daily,
            'weekly' => $weekly,
            'monthly' => $monthly,
        ];
    }

    private function shiftPerformance(array $filters): Collection
    {
        return (clone $this->basePaidOrdersQuery($filters))
            ->leftJoin('shifts', 'shifts.id', '=', 'payment_users.shift_id')
            ->selectRaw("COALESCE(shifts.name, 'Unassigned') as shift_name")
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(orders.grand_total) as revenue')
            ->selectRaw('AVG(orders.grand_total) as average_order_value')
            ->groupBy('shifts.id', 'shifts.name')
            ->orderByDesc('revenue')
            ->get();
    }

    private function basePaidOrdersQuery(array $filters): Builder
    {
        $query = Order::query()
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('users as payment_users', 'payment_users.id', '=', 'payments.processed_by');

        $this->applyPaidOrderFilters($query, $filters);

        return $query;
    }

    private function applyPaidOrderFilters($query, array $filters): void
    {
        $query
            ->where('orders.payment_status', 'paid')
            ->whereBetween(DB::raw($this->paidAtExpression()), [
                $filters['start_at'],
                $filters['end_at'],
            ]);

        if (! empty($filters['shift_id'])) {
            $query->where('payment_users.shift_id', $filters['shift_id']);
        }

        if (! empty($filters['payment_method'])) {
            $query->whereRaw($this->paymentMethodExpression().' = ?', [$filters['payment_method']]);
        }
    }

    private function baseRejectQuery(array $filters): Builder
    {
        $query = RejectItem::query()
            ->whereBetween('reject_items.created_at', [
                $filters['start_at'],
                $filters['end_at'],
            ]);

        if (! empty($filters['shift_id'])) {
            // Rejects are filtered by the staff member who reported the loss.
            $query->whereHas('reportedBy', fn (Builder $userQuery) => $userQuery
                ->where('shift_id', $filters['shift_id']));
        }

        if (! empty($filters['payment_method'])) {
            $query->whereHas('orderItem.order', function (Builder $orderQuery) use ($filters) {
                $orderQuery
                    ->where('payment_method', $filters['payment_method'])
                    ->orWhereHas('payment', fn (Builder $paymentQuery) => $paymentQuery
                        ->where('payment_method', $filters['payment_method']));
            });
        }

        return $query;
    }

    private function paidAtExpression(): string
    {
        return 'COALESCE(payments.paid_at, orders.updated_at)';
    }

    private function paymentMethodExpression(string $fallback = 'orders.payment_method'): string
    {
        return "COALESCE(payments.payment_method, orders.payment_method, {$fallback})";
    }
}
