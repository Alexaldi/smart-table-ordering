@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<table class="fr-table">
    <thead>
        <tr>
            <th>Time</th>
            <th>Order</th>
            <th>Menu</th>
            <th>Qty</th>
            <th>Reason</th>
            <th>Reported by</th>
            <th>Loss</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($report['reject_summary'] as $reject)
            <tr>
                <td>{{ $reject->created_at?->format('d M Y H:i') }}</td>
                <td>{{ $reject->orderItem?->order?->order_code ?? '-' }}</td>
                <td><span class="fr-strong">{{ $reject->orderItem?->menuItem?->name ?? '-' }}</span></td>
                <td>{{ $reject->quantity }}</td>
                <td>{{ $reject->reason }}</td>
                <td>{{ $reject->reportedBy?->name ?? '-' }}</td>
                <td><span class="fr-loss">{{ $money($reject->cost_impact) }}</span></td>
            </tr>
        @empty
            <tr><td colspan="7"><div class="fr-empty"><strong>No rejects</strong>No rejected items found for this filter.</div></td></tr>
        @endforelse
    </tbody>
</table>
