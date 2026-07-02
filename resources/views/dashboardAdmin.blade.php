@extends('layouts.admin')

@push('styles')
<style>
    .adm-dashboard { width: min(100%, 1440px); margin: 0 auto; padding: 1.75rem 1.25rem 2.5rem; overflow-x: hidden; }
    .adm-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
    .adm-title-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .adm-title-icon { width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; }
    .adm-title-icon i { font-size: 21px; }
    .adm-title { font-size: 24px; font-weight: 800; color: #111827; margin: 0 0 4px; letter-spacing: 0; }
    .adm-subtitle { color: #6b7280; font-size: 14px; margin: 0; }
    .adm-user-chip { display: inline-flex; align-items: center; gap: 9px; min-height: 40px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 0 14px; color: #374151; font-weight: 700; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
    .adm-user-chip i { color: #2563eb; }
    .adm-stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .adm-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 8px 22px rgba(15,23,42,.05); }
    .adm-stat { padding: 17px; }
    .adm-stat-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px; }
    .adm-stat-label { color: #6b7280; font-size: 12px; font-weight: 800; }
    .adm-stat-icon { width: 34px; height: 34px; border-radius: 9px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; }
    .adm-stat-value { color: #111827; font-size: 28px; font-weight: 800; line-height: 1; margin-bottom: 6px; }
    .adm-stat-note { color: #9ca3af; font-size: 12px; }
    .adm-ops { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 14px; margin-bottom: 18px; }
    .adm-op { padding: 16px; border-left: 4px solid #2563eb; }
    .adm-op.green { border-left-color: #16a34a; }
    .adm-op.amber { border-left-color: #f59e0b; }
    .adm-op.red { border-left-color: #dc2626; }
    .adm-op-label { color: #6b7280; font-size: 12px; font-weight: 800; margin-bottom: 10px; }
    .adm-op-value { color: #111827; font-size: 24px; font-weight: 900; line-height: 1.15; }
    .adm-op-note { color: #9ca3af; font-size: 12px; margin-top: 6px; }
    .adm-main { display: grid; grid-template-columns: minmax(0, 1.25fr) minmax(320px, .75fr); gap: 18px; align-items: start; }
    .adm-panel-head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 17px 18px; border-bottom: 1px solid #f3f4f6; }
    .adm-panel-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 800; color: #111827; margin: 0; }
    .adm-panel-title i { color: #2563eb; }
    .adm-list { padding: 10px; }
    .adm-row { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 12px; align-items: center; padding: 13px; border-radius: 10px; }
    .adm-row + .adm-row { border-top: 1px solid #f3f4f6; }
    .adm-row:hover { background: #f9fafb; }
    .adm-row-title { font-size: 14px; font-weight: 800; color: #111827; margin-bottom: 4px; }
    .adm-row-note { color: #6b7280; font-size: 13px; }
    .adm-pill { display: inline-flex; align-items: center; justify-content: center; min-height: 30px; border-radius: 999px; padding: 0 12px; background: #eff6ff; color: #1e40af; font-size: 12px; font-weight: 800; white-space: nowrap; }
    .adm-actions { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 10px; padding: 14px; }
    .adm-action { min-height: 58px; display: flex; align-items: center; gap: 11px; border: 1px solid #e5e7eb; border-radius: 10px; background: #f9fafb; padding: 12px; color: #111827; font-size: 14px; font-weight: 800; transition: border-color .15s, background .15s, box-shadow .15s; }
    .adm-action i { width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; border-radius: 8px; background: #eff6ff; color: #2563eb; flex: 0 0 auto; }
    .adm-action:hover { background: #fff; border-color: #93c5fd; color: #111827; box-shadow: 0 4px 12px rgba(37,99,235,.08); text-decoration: none; }
    .adm-payments { padding: 0 14px 14px; display: grid; gap: 8px; }
    .adm-payment-row { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 12px; border: 1px solid #f3f4f6; border-radius: 10px; background: #fff; }
    .adm-payment-method { color: #111827; font-weight: 900; text-transform: uppercase; }
    .adm-payment-note { color: #9ca3af; font-size: 12px; margin-top: 3px; }
    .adm-payment-value { color: #111827; font-weight: 900; white-space: nowrap; }
    .adm-empty { margin: 0 14px 14px; padding: 18px 14px; border-radius: 10px; background: #f9fafb; color: #6b7280; text-align: center; font-size: 13px; }
    @media (max-width: 991.98px) {
        .adm-dashboard { padding: 1.25rem 1rem; }
        .adm-head { align-items: flex-start; flex-direction: column; }
        .adm-main { grid-template-columns: 1fr; }
    }
    @media (max-width: 575.98px) {
        .adm-dashboard { padding: 1rem .75rem; }
        .adm-actions { grid-template-columns: 1fr; }
        .adm-row { grid-template-columns: 1fr; }
        .adm-pill { justify-self: flex-start; }
    }
</style>
@endpush

@section('content')
@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<div class="side-app">
    <div class="adm-dashboard">
        <header class="adm-head">
            <div class="adm-title-wrap">
                <div class="adm-title-icon" aria-hidden="true">
                    <i class="fe fe-home"></i>
                </div>
                <div>
                    <h1 class="adm-title">Dashboard Admin</h1>
                    <p class="adm-subtitle">Pantau data restoran dan kelola kebutuhan kerja staf dari satu tempat.</p>
                </div>
            </div>
            <div class="adm-user-chip">
                <i class="fe fe-user"></i>
                <span>{{ auth()->user()->name }}</span>
            </div>
        </header>

        <section class="adm-stats" aria-label="Ringkasan data restoran">
            <article class="adm-card adm-stat">
                <div class="adm-stat-top">
                    <div class="adm-stat-label">User</div>
                    <div class="adm-stat-icon"><i class="fe fe-users"></i></div>
                </div>
                <div class="adm-stat-value">{{ $totalUsers }}</div>
                <div class="adm-stat-note">{{ $activeUsers }} akun aktif</div>
            </article>

            <article class="adm-card adm-stat">
                <div class="adm-stat-top">
                    <div class="adm-stat-label">Shift</div>
                    <div class="adm-stat-icon"><i class="fe fe-clock"></i></div>
                </div>
                <div class="adm-stat-value">{{ $totalShifts }}</div>
                <div class="adm-stat-note">Jadwal kerja terdaftar</div>
            </article>

            <article class="adm-card adm-stat">
                <div class="adm-stat-top">
                    <div class="adm-stat-label">Meja</div>
                    <div class="adm-stat-icon"><i class="fe fe-grid"></i></div>
                </div>
                <div class="adm-stat-value">{{ $totalTables }}</div>
                <div class="adm-stat-note">Siap dipakai pelanggan</div>
            </article>

            <article class="adm-card adm-stat">
                <div class="adm-stat-top">
                    <div class="adm-stat-label">Menu</div>
                    <div class="adm-stat-icon"><i class="fa fa-coffee"></i></div>
                </div>
                <div class="adm-stat-value">{{ $totalMenus }}</div>
                <div class="adm-stat-note">{{ $activeMenus }} menu aktif</div>
            </article>
        </section>

        <section class="adm-ops" id="adminDashboardOps" data-realtime-url="{{ route('dashboard.realtime') }}" aria-label="Ringkasan operasional hari ini">
            @include('Admin.dashboard.partials.ops', [
                'todayReport' => $todayReport,
                'pendingPaymentOrders' => $pendingPaymentOrders,
                'activeKitchenQueues' => $activeKitchenQueues,
                'completedKitchenQueues' => $completedKitchenQueues,
            ])
        </section>

        <section class="adm-main">
            <div class="adm-card">
                <div class="adm-panel-head">
                    <h2 class="adm-panel-title">
                        <i class="fe fe-activity"></i>
                        <span>Status Data Restoran</span>
                    </h2>
                </div>
                <div class="adm-list">
                    <div class="adm-row">
                        <div>
                            <div class="adm-row-title">Kategori menu</div>
                            <div class="adm-row-note">Kelompokkan menu agar pelanggan lebih mudah memilih.</div>
                        </div>
                        <span class="adm-pill">{{ $totalCategories }} kategori</span>
                    </div>
                    <div class="adm-row">
                        <div>
                            <div class="adm-row-title">Akun staf</div>
                            <div class="adm-row-note">Pastikan hanya staf aktif yang bisa masuk ke halaman kerja.</div>
                        </div>
                        <span class="adm-pill">{{ $activeUsers }} aktif</span>
                    </div>
                    <div class="adm-row">
                        <div>
                            <div class="adm-row-title">Shift kerja</div>
                            <div class="adm-row-note">Kasir dapat masuk sesuai jadwal shift yang ditentukan.</div>
                        </div>
                        <span class="adm-pill">{{ $totalShifts }} shift</span>
                    </div>
                    <div class="adm-row">
                        <div>
                            <div class="adm-row-title">Transaksi hari ini</div>
                            <div class="adm-row-note">Pantau pembayaran berhasil, order menunggu bayar, dan nilai rata-rata transaksi.</div>
                        </div>
                        <span class="adm-pill">{{ $todayReport['total_orders'] }} paid</span>
                    </div>
                    <div class="adm-row">
                        <div>
                            <div class="adm-row-title">Diskon dan reject cost</div>
                            <div class="adm-row-note">Ringkasan potongan dan potensi loss operasional hari ini.</div>
                        </div>
                        <span class="adm-pill">{{ $money($todayReport['total_discount'] + $todayReport['reject_cost']) }}</span>
                    </div>
                </div>
            </div>

            <aside class="adm-card">
                <div class="adm-panel-head">
                    <h2 class="adm-panel-title">
                        <i class="fe fe-zap"></i>
                        <span>Aksi Cepat</span>
                    </h2>
                </div>
                <div class="adm-actions">
                    <a href="{{ route('users.index') }}" class="adm-action">
                        <i class="fe fe-users"></i>
                        <span>Kelola User</span>
                    </a>
                    <a href="{{ route('shifts.index') }}" class="adm-action">
                        <i class="fe fe-clock"></i>
                        <span>Kelola Shift</span>
                    </a>
                    <a href="{{ route('tables.index') }}" class="adm-action">
                        <i class="fe fe-grid"></i>
                        <span>Kelola Meja</span>
                    </a>
                    <a href="{{ route('menu.index') }}" class="adm-action">
                        <i class="fa fa-coffee"></i>
                        <span>Kelola Menu</span>
                    </a>
                    <a href="{{ route('admin.reports.financial') }}" class="adm-action">
                        <i class="fe fe-bar-chart-2"></i>
                        <span>Financial Report</span>
                    </a>
                    <a href="{{ route('admin.orders.index') }}" class="adm-action">
                        <i class="fe fe-clipboard"></i>
                        <span>Order History</span>
                    </a>
                </div>
                <div id="adminDashboardPayments">
                    @include('Admin.dashboard.partials.payments', ['todayReport' => $todayReport])
                </div>
            </aside>
        </section>
    </div>
</div>

<script>
    (function() {
        const opsWrapper = document.getElementById('adminDashboardOps');
        const paymentsWrapper = document.getElementById('adminDashboardPayments');

        if (!opsWrapper || !paymentsWrapper) {
            return;
        }

        let refreshTimer = null;
        let refreshRunning = false;

        window.refreshAdminDashboardSummary = function() {
            clearTimeout(refreshTimer);

            refreshTimer = setTimeout(async function() {
                if (refreshRunning) {
                    return;
                }

                refreshRunning = true;

                try {
                    const response = await fetch(`${opsWrapper.dataset.realtimeUrl}?t=${Date.now()}`, {
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
                    opsWrapper.innerHTML = data.ops_html;
                    paymentsWrapper.innerHTML = data.payments_html;
                } catch (error) {
                    console.error('Failed to refresh admin dashboard:', error);
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

            window.refreshAdminDashboardSummary();
        });
    })();
</script>
@endsection
