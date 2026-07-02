@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<article class="ow-card">
    <div class="ow-panel-head">
        <h2>Reject / Loss Summary</h2>
        <span>Estimated cost impact</span>
    </div>
    <div class="ow-table-wrap">
        <table class="ow-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Order</th>
                    <th>Menu</th>
                    <th>Qty</th>
                    <th>Reason</th>
                    <th class="ow-money">Loss</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($report['reject_summary'] as $reject)
                    <tr>
                        <td data-label="Time">{{ $reject->created_at?->format('d M Y H:i') }}</td>
                        <td data-label="Order">{{ $reject->orderItem?->order?->order_code ?? '-' }}</td>
                        <td data-label="Menu"><span class="ow-strong">{{ $reject->orderItem?->menuItem?->name ?? '-' }}</span></td>
                        <td data-label="Qty">{{ $reject->quantity }}</td>
                        <td data-label="Reason">{{ $reject->reason }}</td>
                        <td class="ow-money" data-label="Loss"><span class="ow-loss">{{ $money($reject->cost_impact) }}</span></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">
                            <div class="ow-empty"><strong>No reject losses</strong><span>No rejected items found for this filter.</span></div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</article>
