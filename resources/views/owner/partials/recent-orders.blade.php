@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<article class="ow-card">
    <div class="ow-panel-head">
        <h2>Recent Paid Orders</h2>
        <span>Read-only transaction view</span>
    </div>
    <div class="ow-table-wrap">
        <table class="ow-table">
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Paid at</th>
                    <th>Table</th>
                    <th>Payment</th>
                    <th class="ow-money">Subtotal</th>
                    <th class="ow-money">Discount</th>
                    <th class="ow-money">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report['recent_paid_orders'] as $order)
                    <tr>
                        <td data-label="Order"><span class="ow-strong">{{ $order->order_code }}</span></td>
                        <td data-label="Paid at">{{ optional($order->payment?->paid_at)->format('d M Y H:i') ?? optional($order->updated_at)->format('d M Y H:i') }}</td>
                        <td data-label="Table">{{ $order->table?->table_number ?? '-' }}</td>
                        <td data-label="Payment"><span class="ow-pill">{{ $order->payment?->payment_method ?? $order->payment_method ?? '-' }}</span></td>
                        <td class="ow-money" data-label="Subtotal">{{ $money($order->subtotal) }}</td>
                        <td class="ow-money" data-label="Discount">{{ $money($order->discount_total) }}</td>
                        <td class="ow-money" data-label="Total"><span class="ow-strong">{{ $money($order->grand_total) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <div class="ow-empty"><strong>No paid orders</strong><span>Paid orders will appear here after payments are completed.</span></div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
