@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
    $cards = [
        ['label' => 'Gross Revenue', 'value' => $money($report['gross_revenue']), 'icon' => 'fe fe-trending-up', 'note' => 'Before discounts and losses'],
        ['label' => 'Net Revenue', 'value' => $money($report['net_revenue']), 'icon' => 'fe fe-pocket', 'note' => 'Gross - discount - reject cost'],
        ['label' => 'Total Orders', 'value' => number_format($report['total_orders'], 0, ',', '.'), 'icon' => 'fe fe-shopping-bag', 'note' => 'Paid orders'],
        ['label' => 'Average Order Value', 'value' => $money($report['average_order_value']), 'icon' => 'fe fe-activity', 'note' => 'Grand total average'],
        ['label' => 'Discount Given', 'value' => $money($report['total_discount']), 'icon' => 'fe fe-tag', 'note' => 'Customer discount total'],
        ['label' => 'Reject Cost', 'value' => $money($report['reject_cost']), 'icon' => 'fe fe-alert-triangle', 'note' => 'Estimated operational loss'],
    ];
@endphp

<section class="ow-kpis" aria-label="Business key metrics">
    @foreach ($cards as $card)
        <article class="ow-card ow-kpi">
            <div class="ow-kpi-top">
                <span>{{ $card['label'] }}</span>
                <i class="{{ $card['icon'] }}"></i>
            </div>
            <div class="ow-kpi-value">{{ $card['value'] }}</div>
            <div class="ow-kpi-note">{{ $card['note'] }}</div>
        </article>
    @endforeach
</section>
