<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Owner Dashboard</title>
    <link href="{{ asset('assets/css/icons.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; font-family: Arial, sans-serif; background: #f4f6fa; color: #111827; }
        .ow-shell { min-height: 100vh; padding: 28px 20px 40px; }
        .ow-page { width: min(100%, 1280px); margin: 0 auto; }
        .ow-header { display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; margin-bottom: 18px; padding: 4px 0; }
        .ow-title-wrap { display: flex; gap: 12px; align-items: center; min-width: 0; }
        .ow-icon { width: 48px; height: 48px; border-radius: 12px; background: #ecfdf5; border: 1px solid #bbf7d0; color: #15803d; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; box-shadow: 0 8px 18px rgba(22, 163, 74, .10); }
        .ow-title { margin: 0 0 5px; font-size: 28px; font-weight: 900; letter-spacing: 0; }
        .ow-subtitle { margin: 0; color: #6b7280; font-size: 14px; }
        .ow-actions { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; justify-content: flex-end; }
        .ow-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 10px 24px rgba(15, 23, 42, .055); }
        .ow-button { min-height: 40px; border: 1px solid #d1d5db; border-radius: 8px; background: #fff; color: #374151; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0 13px; text-decoration: none; cursor: pointer; white-space: nowrap; }
        .ow-button.primary { background: #16a34a; border-color: #16a34a; color: #fff; }
        .ow-button.blue { background: #2563eb; border-color: #2563eb; color: #fff; }
        .ow-filter { padding: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 12px; align-items: end; margin-bottom: 16px; }
        .ow-field label { display: block; margin-bottom: 6px; color: #374151; font-size: 12px; font-weight: 900; }
        .ow-control { width: 100%; min-height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 10px; background: #fff; color: #111827; }
        .ow-filter-actions { display: flex; gap: 8px; align-items: end; }
        .ow-kpis { display: grid; grid-template-columns: repeat(auto-fit, minmax(185px, 1fr)); gap: 14px; margin-bottom: 16px; }
        .ow-kpi { padding: 17px; min-height: 126px; }
        .ow-kpi-top { display: flex; justify-content: space-between; gap: 12px; color: #6b7280; font-size: 12px; font-weight: 900; text-transform: uppercase; margin-bottom: 14px; }
        .ow-kpi-top i { width: 32px; height: 32px; border-radius: 8px; background: #f0fdf4; color: #16a34a; display: flex; align-items: center; justify-content: center; }
        .ow-kpi-value { font-size: 26px; line-height: 1.1; font-weight: 900; color: #111827; }
        .ow-kpi-note { color: #9ca3af; font-size: 12px; margin-top: 7px; }
        .ow-charts { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 16px; align-items: stretch; }
        .ow-chart-card, .owner-chart-card { min-height: 358px; overflow: hidden; display: flex; flex-direction: column; min-width: 0; }
        .ow-panel-head { min-height: 58px; padding: 15px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
        .ow-panel-head h2 { margin: 0; font-size: 15px; color: #111827; font-weight: 900; }
        .ow-panel-head span { color: #9ca3af; font-size: 12px; white-space: nowrap; }
        .ow-chart-box, .owner-chart-box { width: 100%; max-width: 100%; height: 300px; padding: 16px; position: relative; flex: 1 1 auto; min-width: 0; }
        .ow-chart-box canvas, .owner-chart-box canvas { display: block; width: 100% !important; max-width: 100%; height: 100% !important; }
        .ow-tables { display: grid; grid-template-columns: minmax(0, 1.15fr) minmax(320px, .85fr); gap: 16px; align-items: start; }
        .ow-table-wrap { width: 100%; overflow-x: auto; }
        .ow-table { width: 100%; border-collapse: collapse; }
        .ow-table th { text-align: left; padding: 11px 14px; background: #f9fafb; color: #6b7280; font-size: 11px; letter-spacing: .03em; text-transform: uppercase; white-space: nowrap; }
        .ow-table td { padding: 12px 14px; border-top: 1px solid #f3f4f6; color: #374151; vertical-align: middle; font-size: 13px; }
        .ow-money { text-align: right; white-space: nowrap; }
        .ow-strong { color: #111827; font-weight: 900; }
        .ow-pill { display: inline-flex; min-height: 25px; align-items: center; padding: 0 9px; border-radius: 999px; background: #eff6ff; color: #1d4ed8; text-transform: uppercase; font-size: 11px; font-weight: 900; }
        .ow-loss { color: #dc2626; font-weight: 900; }
        .ow-empty { min-height: 180px; padding: 26px 18px; color: #6b7280; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; gap: 5px; }
        .ow-empty strong { color: #111827; font-size: 14px; }
        @media (max-width: 1040px) {
            .ow-tables { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .ow-shell { padding: 18px 12px; }
            .ow-page, .ow-card, .ow-table-wrap { min-width: 0; max-width: 100%; }
            .ow-header { flex-direction: column; }
            .ow-actions { justify-content: flex-start; }
            .ow-actions .ow-button, .ow-actions form, .ow-actions form .ow-button { width: 100%; }
            .ow-filter { grid-template-columns: 1fr; }
            .ow-filter-actions { width: 100%; }
            .ow-filter-actions .ow-button { flex: 1 1 0; }
            .ow-kpi-value { font-size: 23px; }
            .ow-chart-card, .owner-chart-card { min-height: 290px; }
            .ow-chart-box, .owner-chart-box { height: 240px; padding: 12px; }
            .ow-panel-head { align-items: flex-start; flex-direction: column; }
            .ow-table-wrap { overflow-x: visible; }
            .ow-table, .ow-table thead, .ow-table tbody, .ow-table tr, .ow-table th, .ow-table td { display: block; width: 100%; }
            .ow-table thead { display: none; }
            .ow-table tr { padding: 12px 14px; border-top: 1px solid #f3f4f6; }
            .ow-table tr:first-child { border-top: 0; }
            .ow-table td { display: grid; grid-template-columns: 96px minmax(0, 1fr); gap: 10px; align-items: center; padding: 7px 0; border-top: 0; overflow-wrap: anywhere; }
            .ow-table td::before { content: attr(data-label); color: #94a3b8; font-size: 11px; font-weight: 900; text-transform: uppercase; }
            .ow-table td.ow-money { text-align: left; white-space: normal; }
            .ow-table .ow-empty { min-height: 140px; padding: 18px 0; }
            .ow-table td[colspan] { display: block; }
            .ow-table td[colspan]::before { content: none; }
        }
    </style>
</head>
<body>
    @php
        $chartData = [
            'revenueLabels' => $report['revenue_trend']['daily']->pluck('label')->values(),
            'revenueValues' => $report['revenue_trend']['daily']->pluck('revenue')->values(),
            'paymentLabels' => $report['payment_breakdown']->pluck('payment_method')->map(fn ($method) => strtoupper($method))->values(),
            'paymentValues' => $report['payment_breakdown']->pluck('total_revenue')->map(fn ($value) => (float) $value)->values(),
            'menuLabels' => $report['best_selling_menus']->pluck('menu_name')->values(),
            'menuValues' => $report['best_selling_menus']->pluck('quantity_sold')->map(fn ($value) => (int) $value)->values(),
            'shiftLabels' => $report['shift_performance']->pluck('shift_name')->values(),
            'shiftValues' => $report['shift_performance']->pluck('revenue')->map(fn ($value) => (float) $value)->values(),
        ];
    @endphp

    <main class="ow-shell">
        <div class="ow-page">
            <header class="ow-header">
                <div class="ow-title-wrap">
                    <div class="ow-icon"><i class="fe fe-briefcase"></i></div>
                    <div>
                        <h1 class="ow-title">Owner Dashboard</h1>
                        <p class="ow-subtitle">Business performance overview</p>
                    </div>
                </div>
                <div class="ow-actions">
                    <a class="ow-button primary" href="{{ route('owner.reports.export.excel', $filterQuery) }}"><i class="fe fe-download"></i> Export Excel</a>
                    <a class="ow-button blue" href="{{ route('owner.reports.export.pdf', $filterQuery) }}" target="_blank" rel="noopener"><i class="fe fe-printer"></i> Export PDF</a>
                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                        @csrf
                        <button class="ow-button" type="submit"><i class="fe fe-log-out"></i> Logout</button>
                    </form>
                </div>
            </header>

            <form class="ow-card ow-filter" method="GET" action="{{ route('owner.dashboard') }}">
                <div class="ow-field">
                    <label for="start_date">Start date</label>
                    <input class="ow-control" id="start_date" type="date" name="start_date" value="{{ $filters['start_date'] }}" max="{{ $filters['max_date'] }}">
                </div>
                <div class="ow-field">
                    <label for="end_date">End date</label>
                    <input class="ow-control" id="end_date" type="date" name="end_date" value="{{ $filters['end_date'] }}" max="{{ $filters['max_date'] }}">
                </div>
                <div class="ow-field">
                    <label for="shift_id">Shift</label>
                    <select class="ow-control" id="shift_id" name="shift_id">
                        <option value="">All shifts</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}" @selected((string) $filters['shift_id'] === (string) $shift->id)>
                                {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }}-{{ substr($shift->end_time, 0, 5) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="ow-field">
                    <label for="payment_method">Payment</label>
                    <select class="ow-control" id="payment_method" name="payment_method">
                        <option value="">All methods</option>
                        @foreach ($paymentMethods as $method)
                            <option value="{{ $method }}" @selected($filters['payment_method'] === $method)>{{ strtoupper($method) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ow-filter-actions">
                    <button class="ow-button primary" type="submit"><i class="fe fe-filter"></i> Apply</button>
                    <a class="ow-button" href="{{ route('owner.dashboard') }}">Reset</a>
                </div>
            </form>

            @include('owner.partials.kpi', ['report' => $report])
            @include('owner.partials.charts', ['report' => $report])

            <section class="ow-tables">
                @include('owner.partials.recent-orders', ['report' => $report])
                @include('owner.partials.reject-summary', ['report' => $report])
            </section>
        </div>
    </main>

    <script src="{{ asset('assets/plugins/chart/Chart.bundle.js') }}"></script>
    <script>
        (function() {
            const chartData = @json($chartData);
            const money = function(value) {
                return 'Rp ' + Number(value || 0).toLocaleString('id-ID');
            };

            function makeChart(id, config) {
                const element = document.getElementById(id);
                if (!element || typeof Chart === 'undefined') return;
                return new Chart(element.getContext('2d'), config);
            }

            makeChart('revenueTrendChart', {
                type: 'line',
                data: {
                    labels: chartData.revenueLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: chartData.revenueValues,
                        borderColor: '#16a34a',
                        backgroundColor: 'rgba(22, 163, 74, .10)',
                        pointBackgroundColor: '#16a34a',
                        borderWidth: 2,
                        lineTension: .25,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: { callbacks: { label: function(item) { return money(item.yLabel); } } },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, callback: money } }] }
                }
            });

            makeChart('paymentBreakdownChart', {
                type: 'doughnut',
                data: {
                    labels: chartData.paymentLabels,
                    datasets: [{
                        data: chartData.paymentValues,
                        backgroundColor: ['#16a34a', '#2563eb', '#f59e0b', '#64748b'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { position: 'bottom' },
                    tooltips: { callbacks: { label: function(item, data) {
                        const label = data.labels[item.index] || '';
                        const value = data.datasets[0].data[item.index] || 0;
                        return label + ': ' + money(value);
                    } } }
                }
            });

            makeChart('bestSellingMenuChart', {
                type: 'bar',
                data: {
                    labels: chartData.menuLabels,
                    datasets: [{
                        label: 'Qty sold',
                        data: chartData.menuValues,
                        backgroundColor: '#2563eb',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, precision: 0 } }] }
                }
            });

            makeChart('shiftPerformanceChart', {
                type: 'bar',
                data: {
                    labels: chartData.shiftLabels,
                    datasets: [{
                        label: 'Revenue',
                        data: chartData.shiftValues,
                        backgroundColor: '#0f766e',
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    tooltips: { callbacks: { label: function(item) { return money(item.yLabel); } } },
                    scales: { yAxes: [{ ticks: { beginAtZero: true, callback: money } }] }
                }
            });
        })();
    </script>
</body>
</html>
