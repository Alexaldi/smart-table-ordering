@extends('layouts.admin')

@push('styles')
<style>
    .oh-page { width: min(100%, 1440px); margin: 0 auto; padding: 1.75rem 1.25rem 2.5rem; }
    .oh-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 18px; }
    .oh-title-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .oh-icon { width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; }
    .oh-title { margin: 0 0 4px; color: #111827; font-size: 24px; font-weight: 900; }
    .oh-subtitle { margin: 0; color: #6b7280; font-size: 14px; }
    .oh-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
    .oh-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 8px 22px rgba(15,23,42,.05); }
    .oh-btn { min-height: 40px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #374151; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0 13px; font-weight: 900; text-decoration: none; white-space: nowrap; cursor: pointer; }
    .oh-btn.primary { background: #2563eb; border-color: #2563eb; color: #fff; }
    .oh-btn.green { background: #16a34a; border-color: #16a34a; color: #fff; }
    .oh-filter { padding: 16px; margin-bottom: 14px; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; align-items: end; }
    .oh-field label { display: block; margin-bottom: 6px; color: #374151; font-size: 12px; font-weight: 900; }
    .oh-control { width: 100%; min-height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 10px; background: #fff; color: #111827; }
    .oh-filter-actions { display: flex; gap: 8px; align-items: end; }
    .oh-chipbar { display: grid; grid-template-columns: repeat(auto-fit, minmax(170px, 1fr)); gap: 10px; margin-bottom: 14px; }
    .oh-chip { min-height: 46px; display: flex; align-items: center; gap: 9px; padding: 0 14px; border: 1px solid #e5e7eb; border-radius: 10px; background: #fff; color: #64748b; font-size: 13px; font-weight: 800; box-shadow: 0 4px 12px rgba(15,23,42,.035); }
    .oh-chip strong { color: #111827; }
    .oh-tabs { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 14px; }
    .oh-tab { min-height: 38px; border: 1px solid #d1d5db; border-radius: 999px; padding: 0 14px; display: inline-flex; align-items: center; gap: 7px; color: #374151; background: #fff; font-weight: 900; text-decoration: none; }
    .oh-tab.active { background: #eff6ff; color: #1d4ed8; border-color: #bfdbfe; }
    .oh-panel-head { padding: 15px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; gap: 10px; align-items: center; }
    .oh-panel-title { margin: 0; color: #111827; font-size: 15px; font-weight: 900; display: flex; align-items: center; gap: 8px; }
    .oh-muted { color: #9ca3af; font-size: 12px; }
    .oh-table-wrap { width: 100%; overflow-x: auto; }
    .oh-table { width: 100%; border-collapse: collapse; }
    .oh-table th { background: #f9fafb; color: #6b7280; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; text-align: left; padding: 11px 14px; white-space: nowrap; }
    .oh-table td { border-top: 1px solid #f3f4f6; color: #374151; padding: 13px 14px; vertical-align: middle; font-size: 13px; }
    .oh-money, .oh-number { text-align: right; white-space: nowrap; }
    .oh-strong { color: #111827; font-weight: 900; }
    .oh-pill { min-height: 25px; display: inline-flex; align-items: center; border-radius: 999px; padding: 0 9px; background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 900; text-transform: uppercase; white-space: nowrap; }
    .oh-pill.warn { background: #fef3c7; color: #b45309; }
    .oh-pill.loss { background: #fef2f2; color: #b91c1c; }
    .oh-loss { color: #dc2626; font-weight: 900; }
    .oh-empty { padding: 36px 16px; text-align: center; color: #6b7280; }
    .oh-empty strong { display: block; color: #111827; margin-bottom: 4px; }
    .oh-pagination { padding: 13px 16px; border-top: 1px solid #f3f4f6; display: flex; align-items: center; justify-content: flex-end; gap: 12px; flex-wrap: wrap; }
    .oh-pagination nav { margin-left: auto; }
    .oh-pagination .pagination { margin: 0; gap: 4px; }
    .oh-pagination .page-link { min-width: 34px; min-height: 34px; border-radius: 8px; display: inline-flex; align-items: center; justify-content: center; color: #374151; font-weight: 800; }
    .oh-pagination .page-item.active .page-link { background: #2563eb; border-color: #2563eb; color: #fff; }
    .oh-closing-note { margin: 0; color: #64748b; font-size: 13px; line-height: 1.6; }
    .oh-closing { padding: 16px; display: grid; grid-template-columns: minmax(0, 1fr) minmax(320px, .65fr); gap: 16px; }
    .oh-checklist { display: grid; gap: 8px; }
    .oh-check-row { display: flex; justify-content: space-between; gap: 12px; padding: 11px 12px; border: 1px solid #f3f4f6; border-radius: 10px; }
    .oh-check-row span { color: #64748b; }
    .oh-side-stack { display: grid; gap: 12px; align-content: start; }
    .oh-compact-links { padding: 12px; display: grid; gap: 8px; }
    @media (max-width: 1100px) {
        .oh-closing { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .oh-page { padding: 1rem .75rem; }
        .oh-head { flex-direction: column; }
        .oh-actions { justify-content: flex-start; }
        .oh-filter-actions { width: 100%; }
        .oh-filter-actions .oh-btn { flex: 1 1 0; }
        .oh-panel-head { align-items: flex-start; flex-direction: column; }
        .oh-tabs { overflow-x: auto; flex-wrap: nowrap; padding-bottom: 2px; }
        .oh-tab { flex: 0 0 auto; }
    }
</style>
@endpush

@section('content')
@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $tabQuery = fn ($tab) => array_merge($filterQuery, ['tab' => $tab]);
    $exportQuery = fn ($type) => route('admin.orders.export', array_merge(['type' => $type], $filterQuery));
    $financial = $report['financial'];
    $counts = $report['summary_counts'];
    $result = match ($activeTab) {
        'all' => $report['all_orders'],
        'paid' => $report['paid_orders'],
        'pending' => $report['pending_orders'],
        'rejected' => $report['rejected_items'],
        default => null,
    };
    $showingText = $result
        ? 'Showing ' . number_format($result->firstItem() ?? 0, 0, ',', '.') . ' to ' . number_format($result->lastItem() ?? 0, 0, ',', '.') . ' of ' . number_format($result->total(), 0, ',', '.') . ' results'
        : 'Closing checklist';
    $totalText = $result
        ? number_format($result->total(), 0, ',', '.') . ' results'
        : 'Closing checklist';
@endphp

<div class="side-app">
    <main class="oh-page">
        <header class="oh-head">
            <div class="oh-title-wrap">
                <div class="oh-icon"><i class="fe fe-clipboard"></i></div>
                <div>
                    <h1 class="oh-title">Order History</h1>
                    <p class="oh-subtitle">Ledger transaksi untuk cek order, status pembayaran, reject item, dan receipt.</p>
                </div>
            </div>
            <div class="oh-actions">
                <a class="oh-btn green" href="{{ $exportQuery('current') }}"><i class="fe fe-download"></i> Export Current Result</a>
                <a class="oh-btn" href="{{ $exportQuery('paid') }}">Export Paid Orders</a>
                <a class="oh-btn" href="{{ $exportQuery('reject') }}">Export Reject Report</a>
                <a class="oh-btn" href="{{ route('admin.reports.financial', $filterQuery) }}"><i class="fe fe-bar-chart-2"></i> Financial Report</a>
            </div>
        </header>

        <form class="oh-card oh-filter" method="GET" action="{{ route('admin.orders.index') }}">
            <input type="hidden" name="tab" value="{{ $activeTab }}">
            <div class="oh-field"><label>Start date</label><input class="oh-control" type="date" name="start_date" value="{{ $filters['start_date'] }}" max="{{ $filters['max_date'] }}"></div>
            <div class="oh-field"><label>End date</label><input class="oh-control" type="date" name="end_date" value="{{ $filters['end_date'] }}" max="{{ $filters['max_date'] }}"></div>
            <div class="oh-field">
                <label>Shift</label>
                <select class="oh-control" name="shift_id">
                    <option value="">All shifts</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}" @selected((string) $filters['shift_id'] === (string) $shift->id)>{{ $shift->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="oh-field">
                <label>Payment method</label>
                <select class="oh-control" name="payment_method">
                    <option value="">All methods</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}" @selected($filters['payment_method'] === $method)>{{ strtoupper($method) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="oh-field">
                <label>Status</label>
                <select class="oh-control" name="status">
                    <option value="">All statuses</option>
                    @foreach ($orderStatuses as $status)
                        <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ str_replace('_', ' ', strtoupper($status)) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="oh-field">
                <label>Table</label>
                <select class="oh-control" name="table_id">
                    <option value="">All tables</option>
                    @foreach ($tables as $table)
                        <option value="{{ $table->id }}" @selected((string) $filters['table_id'] === (string) $table->id)>{{ $table->table_number }}</option>
                    @endforeach
                </select>
            </div>
            <div class="oh-field">
                <label>Cashier</label>
                <select class="oh-control" name="cashier_id">
                    <option value="">All cashiers</option>
                    @foreach ($cashiers as $cashier)
                        <option value="{{ $cashier->id }}" @selected((string) $filters['cashier_id'] === (string) $cashier->id)>{{ $cashier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="oh-field"><label>Search order code</label><input class="oh-control" type="search" name="search" value="{{ $filters['search'] }}" placeholder="ORD-..."></div>
            <div class="oh-filter-actions">
                <button class="oh-btn primary" type="submit"><i class="fe fe-filter"></i> Apply Filter</button>
                <a class="oh-btn" href="{{ route('admin.orders.index') }}">Reset</a>
            </div>
        </form>

        <section class="oh-chipbar" aria-label="Order summary">
            <span class="oh-chip"><strong>{{ number_format($counts['all'], 0, ',', '.') }}</strong> total orders</span>
            <span class="oh-chip"><strong>{{ number_format($counts['paid'], 0, ',', '.') }}</strong> paid</span>
            <span class="oh-chip"><strong>{{ number_format($counts['pending'], 0, ',', '.') }}</strong> pending</span>
            <span class="oh-chip"><strong>{{ number_format($counts['rejected'], 0, ',', '.') }}</strong> rejected</span>
        </section>

        <nav class="oh-tabs">
            <a class="oh-tab {{ $activeTab === 'all' ? 'active' : '' }}" href="{{ route('admin.orders.index', $tabQuery('all')) }}"><i class="fe fe-list"></i> All Orders</a>
            <a class="oh-tab {{ $activeTab === 'paid' ? 'active' : '' }}" href="{{ route('admin.orders.index', $tabQuery('paid')) }}"><i class="fe fe-check-circle"></i> Paid Orders</a>
            <a class="oh-tab {{ $activeTab === 'pending' ? 'active' : '' }}" href="{{ route('admin.orders.index', $tabQuery('pending')) }}"><i class="fe fe-clock"></i> Pending Payment</a>
            <a class="oh-tab {{ $activeTab === 'rejected' ? 'active' : '' }}" href="{{ route('admin.orders.index', $tabQuery('rejected')) }}"><i class="fe fe-alert-triangle"></i> Rejected Items</a>
            <a class="oh-tab {{ $activeTab === 'closing' ? 'active' : '' }}" href="{{ route('admin.orders.index', $tabQuery('closing')) }}"><i class="fe fe-file-text"></i> Daily Closing Summary</a>
        </nav>

        @if ($activeTab === 'all')
            <section class="oh-card">
                <div class="oh-panel-head"><h2 class="oh-panel-title"><i class="fe fe-list"></i> All Orders</h2><span class="oh-muted">{{ $totalText }}</span></div>
                <div class="oh-table-wrap">
                    <table class="oh-table">
                        <thead><tr><th>Order Code</th><th>Created At</th><th>Table</th><th>Payment</th><th>Payment Status</th><th>Order Status</th><th class="oh-money">Total</th><th>Cashier</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse ($result as $order)
                                <tr>
                                    <td><span class="oh-strong">{{ $order->order_code }}</span></td>
                                    <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                                    <td>{{ $order->table?->table_number ?? '-' }}</td>
                                    <td><span class="oh-pill">{{ $order->payment?->payment_method ?? $order->payment_method ?? '-' }}</span></td>
                                    <td><span class="oh-pill {{ $order->payment_status === 'unpaid' ? 'warn' : '' }}">{{ $order->payment_status }}</span></td>
                                    <td><span class="oh-pill">{{ $order->status }}</span></td>
                                    <td class="oh-money"><span class="oh-strong">{{ $money($order->grand_total) }}</span></td>
                                    <td>{{ $order->payment?->processedBy?->name ?? '-' }}</td>
                                    <td><a class="oh-btn" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="9"><div class="oh-empty"><strong>No orders</strong>No orders match this filter.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="oh-pagination">{{ $result->links() }}</div>
            </section>
        @endif

        @if ($activeTab === 'paid')
            <section class="oh-card">
                <div class="oh-panel-head"><h2 class="oh-panel-title"><i class="fe fe-check-circle"></i> Paid Orders</h2><span class="oh-muted">{{ $totalText }}</span></div>
                <div class="oh-table-wrap">
                    <table class="oh-table">
                        <thead><tr><th>Order Code</th><th>Paid At</th><th>Table</th><th>Cashier</th><th>Payment Method</th><th class="oh-money">Subtotal</th><th class="oh-money">Discount</th><th class="oh-money">Total</th><th class="oh-money">Amount Paid</th><th class="oh-money">Change</th><th>Order Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse ($result as $order)
                                <tr>
                                    <td><span class="oh-strong">{{ $order->order_code }}</span></td>
                                    <td>{{ optional($order->payment?->paid_at)->format('d M Y H:i') ?? optional($order->updated_at)->format('d M Y H:i') }}</td>
                                    <td>{{ $order->table?->table_number ?? '-' }}</td>
                                    <td>{{ $order->payment?->processedBy?->name ?? '-' }}</td>
                                    <td><span class="oh-pill">{{ $order->payment?->payment_method ?? $order->payment_method ?? '-' }}</span></td>
                                    <td class="oh-money">{{ $money($order->subtotal) }}</td>
                                    <td class="oh-money">{{ $money($order->discount_total) }}</td>
                                    <td class="oh-money"><span class="oh-strong">{{ $money($order->grand_total) }}</span></td>
                                    <td class="oh-money">{{ $money($order->payment?->amount_paid) }}</td>
                                    <td class="oh-money">{{ $money($order->payment?->change_amount) }}</td>
                                    <td><span class="oh-pill">{{ $order->status }}</span></td>
                                    <td><a class="oh-btn" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="12"><div class="oh-empty"><strong>No paid orders</strong>No paid transactions match this filter.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="oh-pagination">{{ $result->links() }}</div>
            </section>
        @endif

        @if ($activeTab === 'pending')
            <section class="oh-card">
                <div class="oh-panel-head"><h2 class="oh-panel-title"><i class="fe fe-clock"></i> Pending Payment</h2><span class="oh-muted">{{ $totalText }}</span></div>
                <div class="oh-table-wrap">
                    <table class="oh-table">
                        <thead><tr><th>Order Code</th><th>Created At</th><th>Table</th><th>Payment Method</th><th class="oh-money">Grand Total</th><th>Waiting Duration</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse ($result as $order)
                                <tr>
                                    <td><span class="oh-strong">{{ $order->order_code }}</span></td>
                                    <td>{{ $order->created_at?->format('d M Y H:i') }}</td>
                                    <td>{{ $order->table?->table_number ?? '-' }}</td>
                                    <td><span class="oh-pill warn">{{ $order->payment_method ?? '-' }}</span></td>
                                    <td class="oh-money"><span class="oh-strong">{{ $money($order->grand_total) }}</span></td>
                                    <td>{{ $order->created_at?->diffForHumans(now(), true) }}</td>
                                    <td><span class="oh-pill warn">{{ $order->status }}</span></td>
                                    <td><a class="oh-btn" href="{{ route('admin.orders.show', $order) }}">View</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="8"><div class="oh-empty"><strong>No pending payment</strong>No unpaid orders match this filter.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="oh-pagination">{{ $result->links() }}</div>
            </section>
        @endif

        @if ($activeTab === 'rejected')
            <section class="oh-card">
                <div class="oh-panel-head"><h2 class="oh-panel-title"><i class="fe fe-alert-triangle"></i> Rejected Items</h2><span class="oh-muted">{{ $totalText }}</span></div>
                <div class="oh-table-wrap">
                    <table class="oh-table">
                        <thead><tr><th>Time</th><th>Order Code</th><th>Menu</th><th class="oh-number">Qty</th><th>Reason</th><th>Reported By</th><th class="oh-money">Loss / Cost Impact</th><th>Queue Status</th><th>Action</th></tr></thead>
                        <tbody>
                            @forelse ($result as $reject)
                                @php $queue = $reject->orderItem?->kitchenQueues?->where('reject_item_id', $reject->id)->first(); @endphp
                                <tr>
                                    <td>{{ $reject->created_at?->format('d M Y H:i') }}</td>
                                    <td>{{ $reject->orderItem?->order?->order_code ?? '-' }}</td>
                                    <td><span class="oh-strong">{{ $reject->orderItem?->menuItem?->name ?? '-' }}</span></td>
                                    <td class="oh-number">{{ $reject->quantity }}</td>
                                    <td>{{ $reject->reason }}</td>
                                    <td>{{ $reject->reportedBy?->name ?? '-' }}</td>
                                    <td class="oh-money"><span class="oh-loss">{{ $money($reject->cost_impact) }}</span></td>
                                    <td><span class="oh-pill loss">{{ $queue?->queue_type ?? $reject->action }} / {{ $queue?->status ?? '-' }}</span></td>
                                    <td>
                                        @if ($reject->orderItem?->order)
                                            <a class="oh-btn" href="{{ route('admin.orders.show', $reject->orderItem->order) }}">View Order</a>
                                        @else
                                            -
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9"><div class="oh-empty"><strong>No rejected items</strong>No reject/remake data match this filter.</div></td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="oh-pagination">{{ $result->links() }}</div>
            </section>
        @endif

        @if ($activeTab === 'closing')
            <section class="oh-card">
                <div class="oh-panel-head">
                    <h2 class="oh-panel-title"><i class="fe fe-file-text"></i> Daily Closing Checklist</h2>
                    <span class="oh-muted">{{ $filters['start_date'] }} - {{ $filters['end_date'] }}</span>
                </div>
                <div class="oh-closing">
                    <div class="oh-checklist">
                        <div class="oh-check-row"><span>Date range</span><strong>{{ $filters['start_date'] }} - {{ $filters['end_date'] }}</strong></div>
                        <div class="oh-check-row"><span>Shift</span><strong>{{ $shifts->firstWhere('id', $filters['shift_id'])?->name ?? 'All shifts' }}</strong></div>
                        <div class="oh-check-row"><span>Orders in ledger</span><strong>{{ number_format($counts['all'], 0, ',', '.') }}</strong></div>
                        <div class="oh-check-row"><span>Total paid orders</span><strong>{{ $financial['total_orders'] }}</strong></div>
                        <div class="oh-check-row"><span>Pending payment count</span><strong>{{ $report['daily_summary']['pending_payment_count'] }}</strong></div>
                        <div class="oh-check-row"><span>Cash total</span><strong>{{ $money($financial['cash_total']) }}</strong></div>
                        <div class="oh-check-row"><span>Cashless total</span><strong>{{ $money($financial['cashless_total']) }}</strong></div>
                        <div class="oh-check-row"><span>Discount total</span><strong>{{ $money($financial['total_discount']) }}</strong></div>
                        <div class="oh-check-row"><span>Reject cost</span><strong class="oh-loss">{{ $money($financial['reject_cost']) }}</strong></div>
                        <p class="oh-closing-note">Gunakan checklist ini untuk rekonsiliasi order harian. Analisis revenue, AOV, shift performance, dan best selling menu tetap ada di Financial Report.</p>
                    </div>
                    <div class="oh-side-stack">
                        <div class="oh-card" style="box-shadow:none;">
                            <div class="oh-panel-head"><h3 class="oh-panel-title">Payment Check</h3></div>
                            <div class="oh-checklist" style="padding:12px;">
                                @forelse ($financial['payment_breakdown'] as $payment)
                                    <div class="oh-check-row"><span>{{ strtoupper($payment->payment_method) }} <small class="oh-muted">{{ $payment->total_orders }} orders</small></span><strong>{{ $money($payment->total_revenue) }}</strong></div>
                                @empty
                                    <div class="oh-empty"><strong>No payment data</strong></div>
                                @endforelse
                            </div>
                        </div>
                        <div class="oh-card" style="box-shadow:none;">
                            <div class="oh-panel-head"><h3 class="oh-panel-title">Cashier Handover</h3></div>
                            <div class="oh-checklist" style="padding:12px;">
                                @forelse ($report['daily_summary']['cashier_performance'] as $cashier)
                                    <div class="oh-check-row"><span>{{ $cashier->cashier_name }} <small class="oh-muted">{{ $cashier->total_orders }} orders</small></span><strong>{{ $money($cashier->revenue) }}</strong></div>
                                @empty
                                    <div class="oh-empty"><strong>No cashier data</strong></div>
                                @endforelse
                            </div>
                        </div>
                        <div class="oh-card" style="box-shadow:none;">
                            <div class="oh-panel-head"><h3 class="oh-panel-title">Full Report</h3></div>
                            <div class="oh-compact-links">
                                <a class="oh-btn primary" href="{{ route('admin.reports.financial', $filterQuery) }}"><i class="fe fe-bar-chart-2"></i> Open Financial Report</a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    </main>
</div>
@endsection
