<section class="ow-charts" aria-label="Business charts">
    <article class="ow-card ow-chart-card owner-chart-card">
        <div class="ow-panel-head">
            <h2>Revenue Trend</h2>
            <span>Daily revenue</span>
        </div>
        @if ($report['revenue_trend']['daily']->contains(fn ($item) => (float) $item['revenue'] > 0))
            <div class="ow-chart-box owner-chart-box"><canvas id="revenueTrendChart"></canvas></div>
        @else
            <div class="ow-empty"><strong>No revenue yet</strong><span>Revenue trend will appear when paid orders exist.</span></div>
        @endif
    </article>

    <article class="ow-card ow-chart-card owner-chart-card">
        <div class="ow-panel-head">
            <h2>Payment Method Breakdown</h2>
            <span>{{ $report['total_orders'] }} orders</span>
        </div>
        @if ($report['payment_breakdown']->isNotEmpty())
            <div class="ow-chart-box owner-chart-box"><canvas id="paymentBreakdownChart"></canvas></div>
        @else
            <div class="ow-empty"><strong>No payment data</strong><span>Breakdown will appear after payments are completed.</span></div>
        @endif
    </article>

    <article class="ow-card ow-chart-card owner-chart-card">
        <div class="ow-panel-head">
            <h2>Best Selling Menu</h2>
            <span>By quantity</span>
        </div>
        @if ($report['best_selling_menus']->isNotEmpty())
            <div class="ow-chart-box owner-chart-box"><canvas id="bestSellingMenuChart"></canvas></div>
        @else
            <div class="ow-empty"><strong>No menu sales</strong><span>Best sellers will appear after orders are paid.</span></div>
        @endif
    </article>

    <article class="ow-card ow-chart-card owner-chart-card">
        <div class="ow-panel-head">
            <h2>Shift Performance</h2>
            <span>Revenue by shift</span>
        </div>
        @if ($report['shift_performance']->isNotEmpty())
            <div class="ow-chart-box owner-chart-box"><canvas id="shiftPerformanceChart"></canvas></div>
        @else
            <div class="ow-empty"><strong>No shift data</strong><span>Shift performance appears when payments have a cashier.</span></div>
        @endif
    </article>
</section>
