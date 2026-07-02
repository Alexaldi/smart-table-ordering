@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<table class="fr-table">
    <thead>
        <tr>
            <th>Order</th>
            <th>Paid at</th>
            <th>Table</th>
            <th>Payment</th>
            <th>Cashier</th>
            <th>Subtotal</th>
            <th>Discount</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($report['recent_paid_orders'] as $order)
            <tr>
                <td><span class="fr-strong">{{ $order->order_code }}</span></td>
                <td>{{ optional($order->payment?->paid_at)->format('d M Y H:i') ?? optional($order->updated_at)->format('d M Y H:i') }}</td>
                <td>{{ $order->table?->table_number ?? '-' }}</td>
                <td><span class="fr-pill">{{ $order->payment?->payment_method ?? $order->payment_method ?? '-' }}</span></td>
                <td>{{ $order->payment?->processedBy?->name ?? '-' }}</td>
                <td>{{ $money($order->subtotal) }}</td>
                <td>{{ $money($order->discount_total) }}</td>
                <td><span class="fr-strong">{{ $money($order->grand_total) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="8"><div class="fr-empty"><strong>No paid orders</strong>There are no paid transactions for this filter.</div></td></tr>
        @endforelse
    </tbody>
</table>
