@extends('layouts.admin')

@push('styles')
<style>
    .fr-page { width: min(100%, 1440px); margin: 0 auto; padding: 1.75rem 1.25rem 2.5rem; }
    .fr-head { display: flex; justify-content: space-between; align-items: flex-start; gap: 16px; margin-bottom: 18px; }
    .fr-title-wrap { display: flex; align-items: center; gap: 12px; }
    .fr-icon { width: 44px; height: 44px; border-radius: 10px; background: #ecfdf5; border: 1px solid #bbf7d0; color: #16a34a; display: flex; align-items: center; justify-content: center; }
    .fr-title { margin: 0 0 4px; font-size: 24px; font-weight: 900; color: #111827; }
    .fr-subtitle { margin: 0; color: #6b7280; font-size: 14px; }
    .fr-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 8px 22px rgba(15, 23, 42, .05); }
    .fr-filter { padding: 16px; margin-bottom: 16px; display: grid; grid-template-columns: repeat(auto-fit, minmax(190px, 1fr)); gap: 12px; align-items: end; }
    .fr-field label { display: block; margin-bottom: 6px; color: #374151; font-size: 12px; font-weight: 800; }
    .fr-control { width: 100%; min-height: 40px; border: 1px solid #d1d5db; border-radius: 8px; padding: 0 10px; color: #111827; background: #fff; }
    .fr-btn { min-height: 40px; border: 0; border-radius: 8px; background: #2563eb; color: #fff; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 0 14px; }
    .fr-btn.secondary { background: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; text-decoration: none; }
    .fr-filter-actions { display: flex; gap: 8px; align-items: end; }
    .fr-kpis { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 14px; margin-bottom: 16px; }
    .fr-kpi { padding: 16px; }
    .fr-kpi-top { display: flex; justify-content: space-between; gap: 10px; margin-bottom: 12px; }
    .fr-kpi-label { font-size: 12px; color: #6b7280; font-weight: 900; text-transform: uppercase; }
    .fr-kpi-badge { width: 32px; height: 32px; border-radius: 8px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; }
    .fr-kpi-value { color: #111827; font-size: 24px; font-weight: 900; line-height: 1.1; }
    .fr-kpi-note { margin-top: 6px; color: #9ca3af; font-size: 12px; }
    .fr-grid { display: grid; grid-template-columns: minmax(0, 1fr) minmax(320px, .45fr); gap: 16px; align-items: start; margin-bottom: 16px; }
    .fr-report-layout { display: grid; grid-template-columns: minmax(0, 1fr) minmax(320px, 380px); gap: 16px; align-items: start; }
    .fr-main-column, .fr-side-column { display: grid; gap: 16px; align-content: start; }
    .fr-panel-head { padding: 15px 16px; border-bottom: 1px solid #f3f4f6; display: flex; justify-content: space-between; gap: 10px; align-items: center; }
    .fr-panel-title { margin: 0; font-size: 15px; font-weight: 900; color: #111827; display: flex; align-items: center; gap: 8px; }
    .fr-table-wrap { overflow-x: auto; }
    .fr-table { width: 100%; border-collapse: collapse; }
    .fr-table th { background: #f9fafb; color: #6b7280; font-size: 11px; letter-spacing: .03em; text-transform: uppercase; text-align: left; padding: 11px 14px; white-space: nowrap; }
    .fr-table td { border-top: 1px solid #f3f4f6; color: #374151; padding: 12px 14px; vertical-align: middle; font-size: 13px; }
    .fr-strong { color: #111827; font-weight: 900; }
    .fr-muted { color: #9ca3af; font-size: 12px; }
    .fr-pill { display: inline-flex; align-items: center; min-height: 26px; padding: 0 10px; border-radius: 999px; background: #eff6ff; color: #1d4ed8; font-size: 11px; font-weight: 900; text-transform: uppercase; }
    .fr-loss { color: #dc2626; font-weight: 900; }
    .fr-list { padding: 10px; display: grid; gap: 8px; }
    .fr-list-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 11px 12px; border: 1px solid #f3f4f6; border-radius: 10px; }
    .fr-empty { padding: 34px 16px; text-align: center; color: #6b7280; }
    .fr-empty strong { display: block; color: #111827; margin-bottom: 4px; }
    @media (max-width: 1100px) {
        .fr-grid, .fr-report-layout { grid-template-columns: 1fr; }
    }
    @media (max-width: 640px) {
        .fr-page { padding: 1rem .75rem; }
        .fr-head { flex-direction: column; }
        .fr-filter { grid-template-columns: 1fr; }
        .fr-filter-actions { width: 100%; }
        .fr-filter-actions .fr-btn { flex: 1 1 0; }
    }
</style>
@endpush

@section('content')
@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<div class="side-app">
    <main class="fr-page">
        <header class="fr-head">
            <div class="fr-title-wrap">
                <div class="fr-icon"><i class="fe fe-bar-chart-2"></i></div>
                <div>
                    <h1 class="fr-title">Financial Report</h1>
                    <p class="fr-subtitle">Detailed revenue, discount, payment, and reject investigation report.</p>
                </div>
            </div>
        </header>

        <form class="fr-card fr-filter" method="GET" action="{{ route('admin.reports.financial') }}">
            <div class="fr-field">
                <label for="start_date">Start date</label>
                <input class="fr-control" id="start_date" type="date" name="start_date" value="{{ $filters['start_date'] }}" max="{{ $filters['max_date'] }}">
            </div>
            <div class="fr-field">
                <label for="end_date">End date</label>
                <input class="fr-control" id="end_date" type="date" name="end_date" value="{{ $filters['end_date'] }}" max="{{ $filters['max_date'] }}">
            </div>
            <div class="fr-field">
                <label for="shift_id">Shift</label>
                <select class="fr-control" id="shift_id" name="shift_id">
                    <option value="">All shifts</option>
                    @foreach ($shifts as $shift)
                        <option value="{{ $shift->id }}" @selected((string) $filters['shift_id'] === (string) $shift->id)>
                            {{ $shift->name }} ({{ substr($shift->start_time, 0, 5) }}-{{ substr($shift->end_time, 0, 5) }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="fr-field">
                <label for="payment_method">Payment</label>
                <select class="fr-control" id="payment_method" name="payment_method">
                    <option value="">All methods</option>
                    @foreach ($paymentMethods as $method)
                        <option value="{{ $method }}" @selected($filters['payment_method'] === $method)>{{ strtoupper($method) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="fr-filter-actions">
                <button class="fr-btn" type="submit"><i class="fe fe-filter"></i> Apply</button>
                <a class="fr-btn secondary" href="{{ route('admin.reports.financial') }}">Reset</a>
            </div>
        </form>

        <section class="fr-kpis" id="adminReportStats" data-realtime-url="{{ route('admin.reports.financial.realtime') }}">
            @include('Admin.reports.partials.stats', ['report' => $report])
        </section>

        <section class="fr-report-layout">
            <div class="fr-main-column">
                <div class="fr-card">
                <div class="fr-panel-head">
                    <h2 class="fr-panel-title"><i class="fe fe-list"></i> Paid Transactions</h2>
                    <span class="fr-muted">{{ $filters['start_date'] }} - {{ $filters['end_date'] }}</span>
                </div>
                <div class="fr-table-wrap" id="adminPaidOrders">
                    @include('Admin.reports.partials.orders', ['report' => $report])
                </div>
                </div>

                <div class="fr-card">
                <div class="fr-panel-head">
                    <h2 class="fr-panel-title"><i class="fe fe-alert-triangle"></i> Reject Item Report</h2>
                </div>
                <div class="fr-table-wrap" id="adminRejectReport">
                    @include('Admin.reports.partials.reject', ['report' => $report])
                </div>
            </div>
            </div>

            <aside class="fr-side-column" id="adminBreakdown">
                @include('Admin.reports.partials.breakdown', ['report' => $report])
            </aside>
        </section>
    </main>
</div>

<script>
    (function() {
        const statsWrapper = document.getElementById('adminReportStats');
        const paidOrdersWrapper = document.getElementById('adminPaidOrders');
        const rejectWrapper = document.getElementById('adminRejectReport');
        const breakdownWrapper = document.getElementById('adminBreakdown');

        let refreshTimer = null;
        let refreshRunning = false;

        window.refreshAdminFinancialReport = function() {
            clearTimeout(refreshTimer);

            refreshTimer = setTimeout(async function() {
                if (refreshRunning || !statsWrapper) {
                    return;
                }

                refreshRunning = true;
                const baseUrl = statsWrapper.dataset.realtimeUrl;
                const params = new URLSearchParams(window.location.search);
                params.set('t', Date.now());

                try {
                    const response = await fetch(`${baseUrl}?${params.toString()}`, {
                        method: 'GET',
                        cache: 'no-store',
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                    });

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();

                    statsWrapper.innerHTML = data.stats_html;
                    paidOrdersWrapper.innerHTML = data.orders_html;
                    rejectWrapper.innerHTML = data.reject_html;
                    breakdownWrapper.innerHTML = data.breakdown_html;
                } catch (error) {
                    console.error('Failed to refresh admin financial report:', error);
                } finally {
                    refreshRunning = false;
                }
            }, 300);
        };

        window.addEventListener('sto:notification-created', function(event) {
            const notification = event.detail || {};
            const refreshTypes = [
                'order_paid_cash',
                'order_paid_midtrans',
                'order_item_rejected'
            ];

            if (!refreshTypes.includes(notification.type)) {
                return;
            }

            window.refreshAdminFinancialReport();
        });
    })();
</script>
@endsection
