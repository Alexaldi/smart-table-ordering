@extends('layouts.admin')

@push('styles')
<style>
    .adm-dashboard { padding: 2rem 1.5rem; max-width: 100%; overflow-x: hidden; }
    .adm-head { display: flex; align-items: center; justify-content: space-between; gap: 1rem; margin-bottom: 1.25rem; }
    .adm-title-wrap { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .adm-title-icon { width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; display: flex; align-items: center; justify-content: center; flex: 0 0 auto; }
    .adm-title-icon i { font-size: 21px; }
    .adm-title { font-size: 24px; font-weight: 800; color: #111827; margin: 0 0 4px; letter-spacing: 0; }
    .adm-subtitle { color: #6b7280; font-size: 14px; margin: 0; }
    .adm-user-chip { display: inline-flex; align-items: center; gap: 9px; min-height: 40px; background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; padding: 0 14px; color: #374151; font-weight: 700; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
    .adm-user-chip i { color: #2563eb; }
    .adm-stats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
    .adm-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
    .adm-stat { padding: 17px; }
    .adm-stat-top { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-bottom: 12px; }
    .adm-stat-label { color: #6b7280; font-size: 12px; font-weight: 800; }
    .adm-stat-icon { width: 34px; height: 34px; border-radius: 9px; background: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; }
    .adm-stat-value { color: #111827; font-size: 28px; font-weight: 800; line-height: 1; margin-bottom: 6px; }
    .adm-stat-note { color: #9ca3af; font-size: 12px; }
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
    .adm-placeholder { margin: 14px; padding: 14px; border-radius: 10px; background: #f9fafb; border: 1px dashed #cbd5e1; color: #6b7280; font-size: 13px; line-height: 1.6; }
    @media (max-width: 991.98px) {
        .adm-dashboard { padding: 1.25rem 1rem; }
        .adm-head { align-items: flex-start; flex-direction: column; }
        .adm-stats { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .adm-main { grid-template-columns: 1fr; }
    }
    @media (max-width: 575.98px) {
        .adm-dashboard { padding: 1rem .75rem; }
        .adm-stats, .adm-actions { grid-template-columns: 1fr; }
        .adm-row { grid-template-columns: 1fr; }
        .adm-pill { justify-self: flex-start; }
    }
</style>
@endpush

@section('content')
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
                            <div class="adm-row-title">Pemesanan pelanggan</div>
                            <div class="adm-row-note">Area ini disiapkan untuk ringkasan pesanan saat fitur transaksi sudah lengkap.</div>
                        </div>
                        <span class="adm-pill">Template</span>
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
                </div>
                <div class="adm-placeholder">
                    Ringkasan penjualan, antrian pesanan, dan pembayaran bisa ditempatkan di sini setelah fitur pemesanan sudah siap.
                </div>
            </aside>
        </section>
    </div>
</div>
@endsection
