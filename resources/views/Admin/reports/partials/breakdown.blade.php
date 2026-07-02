@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<div class="fr-card">
    <div class="fr-panel-head">
        <h2 class="fr-panel-title"><i class="fe fe-credit-card"></i> Payment Breakdown</h2>
    </div>
    <div class="fr-list">
        @forelse ($report['payment_breakdown'] as $payment)
            <div class="fr-list-row">
                <div>
                    <div class="fr-strong">{{ strtoupper($payment->payment_method) }}</div>
                    <div class="fr-muted">{{ $payment->total_orders }} orders</div>
                </div>
                <div class="fr-strong">{{ $money($payment->total_revenue) }}</div>
            </div>
        @empty
            <div class="fr-empty"><strong>No payment data</strong>Payment breakdown will appear after orders are paid.</div>
        @endforelse
    </div>
</div>

<div class="fr-card">
    <div class="fr-panel-head">
        <h2 class="fr-panel-title"><i class="fe fe-clock"></i> Shift Performance</h2>
    </div>
    <div class="fr-list">
        @forelse ($report['shift_performance'] as $shift)
            <div class="fr-list-row">
                <div>
                    <div class="fr-strong">{{ $shift->shift_name }}</div>
                    <div class="fr-muted">{{ $shift->total_orders }} orders | AOV {{ $money($shift->average_order_value) }}</div>
                </div>
                <div class="fr-strong">{{ $money($shift->revenue) }}</div>
            </div>
        @empty
            <div class="fr-empty"><strong>No shift data</strong>Shift performance appears when payments have a cashier.</div>
        @endforelse
    </div>
</div>
