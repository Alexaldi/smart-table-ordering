@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Gross Revenue</span><span class="fr-kpi-badge"><i class="fe fe-trending-up"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['gross_revenue']) }}</div>
    <div class="fr-kpi-note">Before discounts and reject loss</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Net Revenue</span><span class="fr-kpi-badge"><i class="fe fe-pocket"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['net_revenue']) }}</div>
    <div class="fr-kpi-note">Gross - discount - reject cost</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Total Orders Paid</span><span class="fr-kpi-badge"><i class="fe fe-shopping-bag"></i></span></div>
    <div class="fr-kpi-value">{{ number_format($report['total_orders'], 0, ',', '.') }}</div>
    <div class="fr-kpi-note">Paid orders in selected period</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Average Order Value</span><span class="fr-kpi-badge"><i class="fe fe-activity"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['average_order_value']) }}</div>
    <div class="fr-kpi-note">Based on grand total</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Cash Total</span><span class="fr-kpi-badge"><i class="fe fe-dollar-sign"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['cash_total']) }}</div>
    <div class="fr-kpi-note">Paid cash orders</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Cashless Total</span><span class="fr-kpi-badge"><i class="fe fe-credit-card"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['cashless_total']) }}</div>
    <div class="fr-kpi-note">QRIS, transfer, and other</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Discount Total</span><span class="fr-kpi-badge"><i class="fe fe-tag"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['total_discount']) }}</div>
    <div class="fr-kpi-note">Discount given to customers</div>
</article>
<article class="fr-card fr-kpi">
    <div class="fr-kpi-top"><span class="fr-kpi-label">Reject Cost</span><span class="fr-kpi-badge"><i class="fe fe-alert-triangle"></i></span></div>
    <div class="fr-kpi-value">{{ $money($report['reject_cost']) }}</div>
    <div class="fr-kpi-note">Operational loss from rejected items</div>
</article>
