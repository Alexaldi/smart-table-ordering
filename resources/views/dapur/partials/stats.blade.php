<article class="kt-stat-card">
    <div class="kt-stat-label">Queued Items</div>
    <div class="kt-stat-value">{{ $stats['queued'] ?? 0 }}</div>
</article>

<article class="kt-stat-card">
    <div class="kt-stat-label">Preparing Items</div>
    <div class="kt-stat-value">{{ $stats['preparing'] ?? 0 }}</div>
</article>

<article class="kt-stat-card">
    <div class="kt-stat-label">Total Quantity</div>
    <div class="kt-stat-value">{{ $stats['total_items'] ?? 0 }}</div>
</article>
