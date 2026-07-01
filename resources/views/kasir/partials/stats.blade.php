<article class="ks-stat-card">
    <div class="ks-stat-top">
        <div class="ks-stat-label">Pesanan Baru</div>
        <div class="ks-stat-icon"><i class="fe fe-bell"></i></div>
    </div>
    <div class="ks-stat-value">{{ $stats['new_orders'] ?? 0 }}</div>
    <div class="ks-stat-note">Menunggu diproses</div>
</article>

<article class="ks-stat-card">
    <div class="ks-stat-top">
        <div class="ks-stat-label">Pesanan Diproses</div>
        <div class="ks-stat-icon"><i class="fe fe-refresh-cw"></i></div>
    </div>
    <div class="ks-stat-value">{{ $stats['processing_orders'] ?? 0 }}</div>
    <div class="ks-stat-note">Sedang disiapkan</div>
</article>

<article class="ks-stat-card">
    <div class="ks-stat-top">
        <div class="ks-stat-label">Pembayaran</div>
        <div class="ks-stat-icon"><i class="fe fe-dollar-sign"></i></div>
    </div>
    <div class="ks-stat-value">{{ $stats['payments'] ?? 0 }}</div>
    <div class="ks-stat-note">Transaksi hari ini</div>
</article>

<article class="ks-stat-card">
    <div class="ks-stat-top">
        <div class="ks-stat-label">Meja Aktif</div>
        <div class="ks-stat-icon"><i class="fe fe-grid"></i></div>
    </div>
    <div class="ks-stat-value">{{ $stats['active_tables'] ?? 0 }}</div>
    <div class="ks-stat-note">Sedang digunakan</div>
</article>
