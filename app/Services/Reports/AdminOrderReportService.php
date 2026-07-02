<?php

namespace App\Services\Reports;

use App\Models\Order;
use App\Models\RejectItem;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AdminOrderReportService
{
    public const ORDER_STATUSES = ['pending_payment', 'paid', 'processing', 'served', 'ready', 'cancelled'];

    public const TABS = ['all', 'paid', 'pending', 'rejected', 'closing'];

    public function __construct(private readonly FinancialReportService $financialReportService) {}

    public function filtersFromRequest(Request $request): array
    {
        $filters = $this->financialReportService->filtersFromRequest($request);

        $tab = $request->input('tab', 'all');
        $tab = match ($tab) {
            'reject' => 'rejected',
            'summary' => 'closing',
            default => $tab,
        };

        return array_merge($filters, [
            'tab' => in_array($tab, self::TABS, true) ? $tab : 'all',
            'status' => $request->filled('status') && in_array($request->input('status'), self::ORDER_STATUSES, true)
                ? $request->input('status')
                : null,
            'table_id' => $request->filled('table_id') ? (int) $request->input('table_id') : null,
            'cashier_id' => $request->filled('cashier_id') ? (int) $request->input('cashier_id') : null,
            'search' => trim((string) $request->input('search', '')) ?: null,
        ]);
    }

    public function filterQuery(array $filters): array
    {
        return array_filter([
            'start_date' => $filters['start_date'] ?? null,
            'end_date' => $filters['end_date'] ?? null,
            'shift_id' => $filters['shift_id'] ?? null,
            'payment_method' => $filters['payment_method'] ?? null,
            'tab' => $filters['tab'] ?? null,
            'status' => $filters['status'] ?? null,
            'table_id' => $filters['table_id'] ?? null,
            'cashier_id' => $filters['cashier_id'] ?? null,
            'search' => $filters['search'] ?? null,
        ], fn ($value) => $value !== null && $value !== '');
    }

    public function report(array $filters): array
    {
        $financial = $this->financialReportService->report($filters);
        $filteredFinancial = array_merge($financial, $this->filteredFinancialTotals($filters));

        return [
            'financial' => $filteredFinancial,
            'all_orders' => $filters['tab'] === 'all' ? $this->allOrders($filters) : null,
            'paid_orders' => $filters['tab'] === 'paid' ? $this->paidOrders($filters) : null,
            'pending_orders' => $filters['tab'] === 'pending' ? $this->pendingOrders($filters) : null,
            'rejected_items' => $filters['tab'] === 'rejected' ? $this->rejectedItems($filters) : null,
            'summary_counts' => [
                'all' => $this->allOrdersQuery($filters)->count('orders.id'),
                'paid' => $this->paidOrdersQuery($filters)->count('orders.id'),
                'pending' => $this->pendingOrdersQuery($filters)->count('orders.id'),
                'rejected' => $this->rejectedItemsQuery($filters)->count('reject_items.id'),
            ],
            'daily_summary' => [
                'pending_payment_count' => $this->pendingOrdersQuery($filters)->count(),
                'total_menu_sold' => $this->totalMenuSold($filters),
                'best_selling_menu' => $financial['best_selling_menus']->first(),
                'cashier_performance' => $this->cashierPerformance($filters),
            ],
        ];
    }

    public function paidOrdersForExport(array $filters): Collection
    {
        return $this->paidOrdersQuery($filters)
            ->latest('payments.paid_at')
            ->get();
    }

    public function allOrdersForExport(array $filters): Collection
    {
        return $this->allOrdersQuery($filters)
            ->latest('orders.created_at')
            ->get();
    }

    public function rejectsForExport(array $filters): Collection
    {
        return $this->rejectedItemsQuery($filters)
            ->latest('reject_items.created_at')
            ->get();
    }

    public function pendingOrdersForExport(array $filters): Collection
    {
        return $this->pendingOrdersQuery($filters)
            ->latest('orders.created_at')
            ->get();
    }

    private function paidOrders(array $filters)
    {
        return $this->paidOrdersQuery($filters)
            ->latest('payments.paid_at')
            ->paginate(10, ['orders.*'])
            ->withQueryString();
    }

    private function pendingOrders(array $filters)
    {
        return $this->pendingOrdersQuery($filters)
            ->latest('orders.created_at')
            ->paginate(10, ['orders.*'])
            ->withQueryString();
    }

    private function rejectedItems(array $filters)
    {
        return $this->rejectedItemsQuery($filters)
            ->latest('reject_items.created_at')
            ->paginate(10, ['reject_items.*'])
            ->withQueryString();
    }

    private function allOrders(array $filters)
    {
        return $this->allOrdersQuery($filters)
            ->latest('orders.created_at')
            ->paginate(10, ['orders.*'])
            ->withQueryString();
    }

    private function allOrdersQuery(array $filters): Builder
    {
        $query = Order::query()
            ->select('orders.*')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('users as cashiers', 'cashiers.id', '=', 'payments.processed_by')
            ->with(['table', 'payment.processedBy.shift', 'orderItems.menuItem'])
            ->whereBetween('orders.created_at', [$filters['start_at'], $filters['end_at']]);

        $this->applyCommonOrderFilters($query, $filters);

        return $query;
    }

    private function paidOrdersQuery(array $filters): Builder
    {
        $query = Order::query()
            ->select('orders.*')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('users as cashiers', 'cashiers.id', '=', 'payments.processed_by')
            ->with(['table', 'payment.processedBy.shift', 'orderItems.menuItem'])
            ->where('orders.payment_status', 'paid')
            ->whereBetween(DB::raw('COALESCE(payments.paid_at, orders.updated_at)'), [$filters['start_at'], $filters['end_at']]);

        $this->applyCommonOrderFilters($query, $filters);

        return $query;
    }

    private function pendingOrdersQuery(array $filters): Builder
    {
        $query = Order::query()
            ->select('orders.*')
            ->with(['table', 'orderItems.menuItem'])
            ->where('orders.payment_status', 'unpaid')
            ->whereBetween('orders.created_at', [$filters['start_at'], $filters['end_at']]);

        if (! empty($filters['payment_method'])) {
            $query->where('orders.payment_method', $filters['payment_method']);
        }

        if (! empty($filters['status'])) {
            $query->where('orders.status', $filters['status']);
        }

        if (! empty($filters['shift_id']) || ! empty($filters['cashier_id'])) {
            $query->whereRaw('1 = 0');
        }

        $this->applyTableAndSearchFilters($query, $filters);

        return $query;
    }

    private function rejectedItemsQuery(array $filters): Builder
    {
        $query = RejectItem::query()
            ->with([
                'orderItem.menuItem',
                'orderItem.kitchenQueues',
                'orderItem.order.table',
                'orderItem.order.payment.processedBy.shift',
                'reportedBy.shift',
            ])
            ->whereBetween('reject_items.created_at', [$filters['start_at'], $filters['end_at']]);

        if (! empty($filters['shift_id'])) {
            $query->whereHas('reportedBy', fn (Builder $userQuery) => $userQuery
                ->where('shift_id', $filters['shift_id']));
        }

        if (! empty($filters['cashier_id'])) {
            $query->whereHas('orderItem.order.payment', fn (Builder $paymentQuery) => $paymentQuery
                ->where('processed_by', $filters['cashier_id']));
        }

        if (! empty($filters['payment_method'])) {
            $query->whereHas('orderItem.order', fn (Builder $orderQuery) => $orderQuery
                ->where('payment_method', $filters['payment_method'])
                ->orWhereHas('payment', fn (Builder $paymentQuery) => $paymentQuery
                    ->where('payment_method', $filters['payment_method'])));
        }

        if (! empty($filters['table_id'])) {
            $query->whereHas('orderItem.order', fn (Builder $orderQuery) => $orderQuery
                ->where('table_id', $filters['table_id']));
        }

        if (! empty($filters['search'])) {
            $query->whereHas('orderItem.order', fn (Builder $orderQuery) => $orderQuery
                ->where('order_code', 'like', '%'.$filters['search'].'%'));
        }

        return $query;
    }

    private function applyCommonOrderFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['shift_id'])) {
            $query->where('cashiers.shift_id', $filters['shift_id']);
        }

        if (! empty($filters['payment_method'])) {
            $query->whereRaw('COALESCE(payments.payment_method, orders.payment_method) = ?', [$filters['payment_method']]);
        }

        if (! empty($filters['status'])) {
            $query->where('orders.status', $filters['status']);
        }

        if (! empty($filters['cashier_id'])) {
            $query->where('payments.processed_by', $filters['cashier_id']);
        }

        $this->applyTableAndSearchFilters($query, $filters);
    }

    private function applyTableAndSearchFilters(Builder $query, array $filters): void
    {
        if (! empty($filters['table_id'])) {
            $query->where('orders.table_id', $filters['table_id']);
        }

        if (! empty($filters['search'])) {
            $query->where('orders.order_code', 'like', '%'.$filters['search'].'%');
        }
    }

    private function totalMenuSold(array $filters): int
    {
        $query = DB::table('order_items')
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->leftJoin('payments', 'payments.order_id', '=', 'orders.id')
            ->leftJoin('users as cashiers', 'cashiers.id', '=', 'payments.processed_by')
            ->where('orders.payment_status', 'paid')
            ->whereBetween(DB::raw('COALESCE(payments.paid_at, orders.updated_at)'), [$filters['start_at'], $filters['end_at']]);

        if (! empty($filters['shift_id'])) {
            $query->where('cashiers.shift_id', $filters['shift_id']);
        }

        if (! empty($filters['payment_method'])) {
            $query->whereRaw('COALESCE(payments.payment_method, orders.payment_method) = ?', [$filters['payment_method']]);
        }

        if (! empty($filters['cashier_id'])) {
            $query->where('payments.processed_by', $filters['cashier_id']);
        }

        if (! empty($filters['table_id'])) {
            $query->where('orders.table_id', $filters['table_id']);
        }

        if (! empty($filters['search'])) {
            $query->where('orders.order_code', 'like', '%'.$filters['search'].'%');
        }

        return (int) $query->sum('order_items.quantity');
    }

    private function cashierPerformance(array $filters): Collection
    {
        return User::query()
            ->join('payments', 'payments.processed_by', '=', 'users.id')
            ->join('orders', 'orders.id', '=', 'payments.order_id')
            ->where('orders.payment_status', 'paid')
            ->whereBetween(DB::raw('COALESCE(payments.paid_at, orders.updated_at)'), [$filters['start_at'], $filters['end_at']])
            ->when(! empty($filters['shift_id']), fn (Builder $query) => $query->where('users.shift_id', $filters['shift_id']))
            ->when(! empty($filters['payment_method']), fn (Builder $query) => $query->whereRaw('COALESCE(payments.payment_method, orders.payment_method) = ?', [$filters['payment_method']]))
            ->when(! empty($filters['cashier_id']), fn (Builder $query) => $query->where('users.id', $filters['cashier_id']))
            ->when(! empty($filters['table_id']), fn (Builder $query) => $query->where('orders.table_id', $filters['table_id']))
            ->when(! empty($filters['search']), fn (Builder $query) => $query->where('orders.order_code', 'like', '%'.$filters['search'].'%'))
            ->selectRaw('users.name as cashier_name')
            ->selectRaw('COUNT(orders.id) as total_orders')
            ->selectRaw('SUM(orders.grand_total) as revenue')
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('revenue')
            ->get();
    }

    private function filteredFinancialTotals(array $filters): array
    {
        $paidOrdersQuery = $this->paidOrdersQuery($filters);
        $totalOrders = (int) (clone $paidOrdersQuery)->count('orders.id');
        $grossRevenue = (float) (clone $paidOrdersQuery)->sum('orders.subtotal');
        $totalDiscount = (float) (clone $paidOrdersQuery)->sum('orders.discount_total');
        $rejectCost = (float) $this->rejectedItemsQuery($filters)->sum('reject_items.cost_impact');

        $cashTotal = (float) (clone $paidOrdersQuery)
            ->whereRaw('COALESCE(payments.payment_method, orders.payment_method) = ?', ['cash'])
            ->sum('orders.grand_total');

        $cashlessTotal = (float) (clone $paidOrdersQuery)
            ->whereRaw('COALESCE(payments.payment_method, orders.payment_method) <> ?', ['cash'])
            ->sum('orders.grand_total');

        return [
            'gross_revenue' => $grossRevenue,
            'total_discount' => $totalDiscount,
            'reject_cost' => $rejectCost,
            'net_revenue' => max(0, $grossRevenue - $totalDiscount - $rejectCost),
            'total_orders' => $totalOrders,
            'average_order_value' => $totalOrders > 0
                ? (float) (clone $paidOrdersQuery)->avg('orders.grand_total')
                : 0.0,
            'cash_total' => $cashTotal,
            'cashless_total' => $cashlessTotal,
        ];
    }
}
