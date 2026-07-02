@extends('layouts.admin')

@push('styles')
<style>
    .od-page { width: min(100%, 1440px); margin: 0 auto; padding: 1.75rem 1.25rem 2.5rem; }
    .od-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 18px; }
    .od-title-line { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 6px; }
    .od-title { margin: 0; color: #111827; font-size: 26px; font-weight: 900; letter-spacing: 0; }
    .od-subtitle { margin: 0; color: #6b7280; font-size: 14px; }
    .od-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 8px; }
    .od-btn { min-height: 40px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #374151; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0 13px; font-weight: 900; text-decoration: none; cursor: pointer; white-space: nowrap; }
    .od-btn.primary { background: #2563eb; border-color: #2563eb; color: #fff; }
    .od-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 8px 22px rgba(15,23,42,.05); }
    .od-pill { min-height: 27px; display: inline-flex; align-items: center; border-radius: 999px; padding: 0 10px; background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 900; text-transform: uppercase; white-space: nowrap; }
    .od-pill.good { background: #dcfce7; color: #15803d; }
    .od-pill.warn { background: #fef3c7; color: #b45309; }
    .od-pill.loss { background: #fef2f2; color: #b91c1c; }
    .od-overview { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; margin-bottom: 16px; }
    .od-overview-card { padding: 14px; }
    .od-label { color: #6b7280; font-size: 11px; font-weight: 900; text-transform: uppercase; margin-bottom: 7px; }
    .od-value { color: #111827; font-size: 15px; font-weight: 900; line-height: 1.35; }
    .od-layout { display: grid; grid-template-columns: minmax(0, 1fr) 360px; gap: 16px; align-items: start; }
    .od-main { display: grid; gap: 16px; min-width: 0; }
    .od-side { position: sticky; top: 90px; display: grid; gap: 16px; }
    .od-panel-head { padding: 15px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .od-panel-title { margin: 0; color: #111827; font-size: 15px; font-weight: 900; display: flex; align-items: center; gap: 8px; }
    .od-table-wrap { overflow-x: auto; }
    .od-table { width: 100%; border-collapse: collapse; }
    .od-table th { background: #f9fafb; color: #6b7280; font-size: 11px; text-transform: uppercase; letter-spacing: .03em; text-align: left; padding: 11px 14px; white-space: nowrap; }
    .od-table td { border-top: 1px solid #f3f4f6; color: #374151; padding: 12px 14px; vertical-align: middle; font-size: 13px; }
    .od-money, .od-number { text-align: right; white-space: nowrap; }
    .od-strong { color: #111827; font-weight: 900; }
    .od-muted { color: #9ca3af; font-size: 12px; }
    .od-receipt { padding: 16px; }
    .od-receipt-row { display: flex; justify-content: space-between; gap: 12px; padding: 9px 0; border-bottom: 1px solid #f3f4f6; color: #374151; }
    .od-receipt-row:last-child { border-bottom: 0; }
    .od-receipt-total { margin-top: 10px; padding: 13px 0 4px; border-top: 2px solid #111827; display: flex; justify-content: space-between; gap: 12px; color: #111827; font-size: 20px; font-weight: 900; }
    .od-empty { padding: 34px 16px; text-align: center; color: #6b7280; }
    .od-empty strong { display: block; color: #111827; margin-bottom: 4px; }
    .od-timeline { padding: 16px; display: grid; gap: 12px; }
    .od-timeline-item { display: grid; grid-template-columns: 24px minmax(0, 1fr); gap: 10px; }
    .od-dot { width: 12px; height: 12px; border-radius: 999px; background: #2563eb; margin: 5px auto 0; box-shadow: 0 0 0 5px #eff6ff; }
    .od-time-title { color: #111827; font-weight: 900; }
    .od-time-meta { color: #6b7280; font-size: 12px; margin-top: 3px; }
    @media (max-width: 1100px) {
        .od-layout { grid-template-columns: 1fr; }
        .od-side { position: static; }
        .od-overview { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 640px) {
        .od-page { padding: 1rem .75rem; }
        .od-header { flex-direction: column; }
        .od-actions { justify-content: flex-start; }
        .od-overview { grid-template-columns: 1fr; }
        .od-title { font-size: 22px; }
    }
    @media print {
        body { background: #fff !important; color: #000 !important; }
        .app-sidebar, .app-sidebar__overlay, .app-header, .header, .mobile-header, .footer, #back-to-top, .od-actions { display: none !important; }
        .app-content, .side-app, .od-page { margin: 0 !important; padding: 0 !important; }
        .od-card { box-shadow: none !important; border-color: #ddd !important; break-inside: avoid; }
        .od-layout, .od-overview { display: block; }
        .od-side { position: static; margin-top: 14px; }
        .od-main { display: block; }
        .od-main > .od-card { margin-bottom: 14px; }
        .od-table th { background: #f2f2f2 !important; color: #000 !important; }
        .od-pill { border: 1px solid #ccc; background: #fff !important; color: #000 !important; }
    }
</style>
@endpush

@section('content')
@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $rejects = $order->orderItems->flatMap->rejectItems->sortByDesc('created_at');
    $queues = $order->orderItems->flatMap->kitchenQueues->sortBy('queued_at');
    $paymentStatusClass = $order->payment_status === 'paid' ? 'good' : 'warn';
    $timeline = collect([
        ['title' => 'Order created', 'time' => $order->created_at, 'meta' => 'Customer order entered the system.'],
        ['title' => $order->payment_status === 'paid' ? 'Payment received' : 'Waiting for payment', 'time' => $order->payment?->paid_at, 'meta' => strtoupper($order->payment?->payment_method ?? $order->payment_method ?? 'unpaid')],
    ]);

    foreach ($queues as $queue) {
        $timeline->push([
            'title' => $queue->queue_type === 'remake' ? 'Remake sent to kitchen' : 'Sent to kitchen',
            'time' => $queue->queued_at,
            'meta' => ($queue->orderItem?->menuItem?->name ?? 'Menu item') . ' | ' . $queue->status,
        ]);

        if ($queue->done_at) {
            $timeline->push([
                'title' => 'Kitchen item done',
                'time' => $queue->done_at,
                'meta' => ($queue->orderItem?->menuItem?->name ?? 'Menu item') . ' completed',
            ]);
        }
    }

    foreach ($rejects as $reject) {
        $timeline->push([
            'title' => 'Rejected item / remake',
            'time' => $reject->created_at,
            'meta' => ($reject->orderItem?->menuItem?->name ?? 'Menu item') . ' | qty ' . $reject->quantity,
        ]);
    }

    $timeline = $timeline->sortBy(fn ($event) => $event['time']?->timestamp ?? PHP_INT_MAX)->values();
@endphp

<div class="side-app">
    <main class="od-page">
        <header class="od-header">
            <div>
                <div class="od-title-line">
                    <h1 class="od-title">{{ $order->order_code }}</h1>
                    <span class="od-pill">{{ $order->status }}</span>
                    <span class="od-pill {{ $paymentStatusClass }}">{{ $order->payment_status }}</span>
                </div>
                <p class="od-subtitle">Read-only order detail for admin investigation and closing report.</p>
            </div>
            <div class="od-actions">
                <a class="od-btn" href="{{ route('admin.orders.index') }}"><i class="fe fe-arrow-left"></i> Back to Orders</a>
                <button class="od-btn primary" type="button" onclick="window.print()"><i class="fe fe-printer"></i> Print Receipt</button>
            </div>
        </header>

        <section class="od-overview">
            <article class="od-card od-overview-card"><div class="od-label">Table</div><div class="od-value">{{ $order->table?->table_number ?? '-' }}</div></article>
            <article class="od-card od-overview-card"><div class="od-label">Cashier</div><div class="od-value">{{ $order->payment?->processedBy?->name ?? '-' }}</div></article>
            <article class="od-card od-overview-card"><div class="od-label">Created At</div><div class="od-value">{{ $order->created_at?->format('d M Y H:i') }}</div></article>
            <article class="od-card od-overview-card"><div class="od-label">Paid At / Status</div><div class="od-value">{{ $order->payment?->paid_at?->format('d M Y H:i') ?? strtoupper($order->payment_status) }}</div></article>
        </section>

        <section class="od-layout">
            <div class="od-main">
                <article class="od-card">
                    <div class="od-panel-head"><h2 class="od-panel-title"><i class="fe fe-shopping-bag"></i> Ordered Items</h2><span class="od-muted">{{ $order->orderItems->sum('quantity') }} item(s)</span></div>
                    <div class="od-table-wrap">
                        <table class="od-table">
                            <thead><tr><th>Menu</th><th class="od-number">Qty</th><th class="od-money">Unit Price</th><th class="od-money">Discount</th><th class="od-money">Subtotal</th><th>Status</th></tr></thead>
                            <tbody>
                                @foreach ($order->orderItems as $item)
                                    <tr>
                                        <td><span class="od-strong">{{ $item->menuItem?->name ?? '-' }}</span>@if ($item->notes)<div class="od-muted">{{ $item->notes }}</div>@endif</td>
                                        <td class="od-number">{{ $item->quantity }}</td>
                                        <td class="od-money">{{ $money($item->unit_price) }}</td>
                                        <td class="od-money">{{ $money($item->discount_amount) }}</td>
                                        <td class="od-money"><span class="od-strong">{{ $money($item->subtotal) }}</span></td>
                                        <td><span class="od-pill">{{ $item->status }}</span></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="od-card">
                    <div class="od-panel-head"><h2 class="od-panel-title"><i class="fe fe-alert-triangle"></i> Reject / Remake History</h2></div>
                    <div class="od-table-wrap">
                        <table class="od-table">
                            <thead><tr><th>Time</th><th>Menu</th><th class="od-number">Qty</th><th>Reason</th><th>Reported By</th><th class="od-money">Loss</th><th>Queue Status</th></tr></thead>
                            <tbody>
                                @forelse ($rejects as $reject)
                                    @php $queue = $queues->where('reject_item_id', $reject->id)->first(); @endphp
                                    <tr>
                                        <td>{{ $reject->created_at?->format('d M Y H:i') }}</td>
                                        <td><span class="od-strong">{{ $reject->orderItem?->menuItem?->name ?? '-' }}</span></td>
                                        <td class="od-number">{{ $reject->quantity }}</td>
                                        <td>{{ $reject->reason }}</td>
                                        <td>{{ $reject->reportedBy?->name ?? '-' }}</td>
                                        <td class="od-money"><span class="od-loss">{{ $money($reject->cost_impact) }}</span></td>
                                        <td><span class="od-pill loss">{{ $queue?->queue_type ?? $reject->action }} / {{ $queue?->status ?? '-' }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7"><div class="od-empty"><strong>No rejected items for this order.</strong>This order has no reject or remake history.</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="od-card">
                    <div class="od-panel-head"><h2 class="od-panel-title"><i class="fe fe-package"></i> Kitchen Queue / Production History</h2></div>
                    <div class="od-table-wrap">
                        <table class="od-table">
                            <thead><tr><th>Menu Item</th><th>Queue Type</th><th class="od-number">Qty</th><th>Status</th><th>Queued At</th><th>Done At</th><th>Confirmed By</th></tr></thead>
                            <tbody>
                                @forelse ($queues as $queue)
                                    <tr>
                                        <td><span class="od-strong">{{ $queue->orderItem?->menuItem?->name ?? '-' }}</span></td>
                                        <td><span class="od-pill">{{ $queue->queue_type }}</span></td>
                                        <td class="od-number">{{ $queue->quantity }}</td>
                                        <td><span class="od-pill">{{ $queue->status }}</span></td>
                                        <td>{{ $queue->queued_at?->format('d M Y H:i') ?? '-' }}</td>
                                        <td>{{ $queue->done_at?->format('d M Y H:i') ?? '-' }}</td>
                                        <td>{{ $queue->confirmedBy?->name ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7"><div class="od-empty"><strong>No kitchen queue data.</strong>Kitchen production history will appear after the order is sent to kitchen.</div></td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </article>

                <article class="od-card">
                    <div class="od-panel-head"><h2 class="od-panel-title"><i class="fe fe-activity"></i> Order Timeline</h2></div>
                    <div class="od-timeline">
                        @foreach ($timeline as $event)
                            <div class="od-timeline-item">
                                <span class="od-dot"></span>
                                <div>
                                    <div class="od-time-title">{{ $event['title'] }}</div>
                                    <div class="od-time-meta">{{ $event['time']?->format('d M Y H:i') ?? 'Timestamp not available' }} | {{ $event['meta'] }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            </div>

            <aside class="od-side">
                <article class="od-card">
                    <div class="od-panel-head"><h2 class="od-panel-title"><i class="fe fe-credit-card"></i> Payment Summary</h2></div>
                    <div class="od-receipt">
                        <div class="od-receipt-row"><span>Payment method</span><strong>{{ strtoupper($order->payment?->payment_method ?? $order->payment_method ?? '-') }}</strong></div>
                        <div class="od-receipt-row"><span>Payment status</span><strong>{{ strtoupper($order->payment_status) }}</strong></div>
                        <div class="od-receipt-row"><span>Subtotal</span><strong>{{ $money($order->subtotal) }}</strong></div>
                        <div class="od-receipt-row"><span>Discount</span><strong>{{ $money($order->discount_total) }}</strong></div>
                        <div class="od-receipt-total"><span>Grand Total</span><span>{{ $money($order->grand_total) }}</span></div>
                        <div class="od-receipt-row"><span>Amount paid</span><strong>{{ $money($order->payment?->amount_paid) }}</strong></div>
                        <div class="od-receipt-row"><span>Change</span><strong>{{ $money($order->payment?->change_amount) }}</strong></div>
                        <div class="od-receipt-row"><span>Paid at</span><strong>{{ $order->payment?->paid_at?->format('d M Y H:i') ?? '-' }}</strong></div>
                        <div class="od-receipt-row"><span>Processed by</span><strong>{{ $order->payment?->processedBy?->name ?? '-' }}</strong></div>
                    </div>
                </article>

                <article class="od-card">
                    <div class="od-panel-head"><h2 class="od-panel-title">Quick Info</h2></div>
                    <div class="od-receipt">
                        <div class="od-receipt-row"><span>Order code</span><strong>{{ $order->order_code }}</strong></div>
                        <div class="od-receipt-row"><span>Table</span><strong>{{ $order->table?->table_number ?? '-' }}</strong></div>
                        <div class="od-receipt-row"><span>Customer</span><strong>{{ $order->customer_name ?? '-' }}</strong></div>
                        <div class="od-receipt-row"><span>Phone</span><strong>{{ $order->customer_phone ?? '-' }}</strong></div>
                        <div class="od-receipt-row"><span>Email</span><strong>{{ $order->customer_email ?? '-' }}</strong></div>
                    </div>
                </article>
            </aside>
        </section>
    </main>
</div>
@endsection
