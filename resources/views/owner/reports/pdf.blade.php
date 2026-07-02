<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Owner Report</title>
    <style>
        body { margin: 0; font-family: Arial, sans-serif; color: #111827; background: #fff; font-size: 12px; }
        .page { padding: 28px; }
        .header { display: flex; justify-content: space-between; gap: 18px; border-bottom: 2px solid #111827; padding-bottom: 14px; margin-bottom: 18px; }
        h1 { margin: 0 0 5px; font-size: 24px; }
        h2 { margin: 22px 0 10px; font-size: 15px; }
        .muted { color: #6b7280; }
        .kpis { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; margin-bottom: 10px; }
        .kpi { border: 1px solid #e5e7eb; border-radius: 8px; padding: 10px; }
        .label { color: #6b7280; font-size: 10px; font-weight: 700; text-transform: uppercase; margin-bottom: 6px; }
        .value { font-size: 16px; font-weight: 800; }
        table { width: 100%; border-collapse: collapse; page-break-inside: auto; }
        tr { page-break-inside: avoid; page-break-after: auto; }
        th { background: #f3f4f6; color: #374151; text-align: left; font-size: 10px; text-transform: uppercase; padding: 7px; border: 1px solid #e5e7eb; }
        td { padding: 7px; border: 1px solid #e5e7eb; vertical-align: top; }
        .right { text-align: right; }
        .loss { color: #dc2626; font-weight: 800; }
        .print-note { margin-top: 18px; color: #6b7280; font-size: 11px; }
        @media print {
            .print-note { display: none; }
            .page { padding: 0; }
        }
    </style>
</head>
<body>
@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp
<main class="page">
    <header class="header">
        <div>
            <h1>Owner Report</h1>
            <div class="muted">Business performance overview</div>
        </div>
        <div class="muted">
            Period: {{ $filters['start_date'] }} - {{ $filters['end_date'] }}<br>
            Generated: {{ now()->format('d M Y H:i') }}
        </div>
    </header>

    <section class="kpis">
        <div class="kpi"><div class="label">Gross Revenue</div><div class="value">{{ $money($report['gross_revenue']) }}</div></div>
        <div class="kpi"><div class="label">Net Revenue</div><div class="value">{{ $money($report['net_revenue']) }}</div></div>
        <div class="kpi"><div class="label">Total Orders</div><div class="value">{{ number_format($report['total_orders'], 0, ',', '.') }}</div></div>
        <div class="kpi"><div class="label">Average Order Value</div><div class="value">{{ $money($report['average_order_value']) }}</div></div>
        <div class="kpi"><div class="label">Discount Given</div><div class="value">{{ $money($report['total_discount']) }}</div></div>
        <div class="kpi"><div class="label">Reject Cost</div><div class="value">{{ $money($report['reject_cost']) }}</div></div>
    </section>

    <h2>Payment Method Breakdown</h2>
    <table>
        <thead><tr><th>Payment</th><th class="right">Orders</th><th class="right">Revenue</th></tr></thead>
        <tbody>
            @forelse ($report['payment_breakdown'] as $payment)
                <tr>
                    <td>{{ strtoupper($payment->payment_method) }}</td>
                    <td class="right">{{ $payment->total_orders }}</td>
                    <td class="right">{{ $money($payment->total_revenue) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No payment data.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Best Selling Menu</h2>
    <table>
        <thead><tr><th>Menu</th><th class="right">Qty Sold</th><th class="right">Revenue</th></tr></thead>
        <tbody>
            @forelse ($bestSellingMenus as $menu)
                <tr>
                    <td>{{ $menu->menu_name }}</td>
                    <td class="right">{{ $menu->quantity_sold }}</td>
                    <td class="right">{{ $money($menu->revenue) }}</td>
                </tr>
            @empty
                <tr><td colspan="3">No menu sales.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Recent Paid Orders</h2>
    <table>
        <thead><tr><th>Paid At</th><th>Order</th><th>Table</th><th>Payment</th><th class="right">Total</th></tr></thead>
        <tbody>
            @forelse ($paidOrders->take(40) as $order)
                <tr>
                    <td>{{ optional($order->payment?->paid_at)->format('d M Y H:i') ?? optional($order->updated_at)->format('d M Y H:i') }}</td>
                    <td>{{ $order->order_code }}</td>
                    <td>{{ $order->table?->table_number ?? '-' }}</td>
                    <td>{{ strtoupper($order->payment?->payment_method ?? $order->payment_method ?? '-') }}</td>
                    <td class="right">{{ $money($order->grand_total) }}</td>
                </tr>
            @empty
                <tr><td colspan="5">No paid orders.</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Reject / Loss Summary</h2>
    <table>
        <thead><tr><th>Time</th><th>Order</th><th>Menu</th><th class="right">Qty</th><th>Reason</th><th class="right">Loss</th></tr></thead>
        <tbody>
            @forelse ($rejects->take(40) as $reject)
                <tr>
                    <td>{{ $reject->created_at?->format('d M Y H:i') }}</td>
                    <td>{{ $reject->orderItem?->order?->order_code ?? '-' }}</td>
                    <td>{{ $reject->orderItem?->menuItem?->name ?? '-' }}</td>
                    <td class="right">{{ $reject->quantity }}</td>
                    <td>{{ $reject->reason }}</td>
                    <td class="right loss">{{ $money($reject->cost_impact) }}</td>
                </tr>
            @empty
                <tr><td colspan="6">No reject losses.</td></tr>
            @endforelse
        </tbody>
    </table>

    @if ($isPdfFallback ?? false)
        <div class="print-note">
            PDF library is not installed in composer.json. Use browser print or save as PDF from this professional printable report.
        </div>
    @endif
</main>
@if ($isPdfFallback ?? false)
    <script>
        window.addEventListener('load', function() {
            setTimeout(function() { window.print(); }, 300);
        });
    </script>
@endif
</body>
</html>
