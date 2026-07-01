@forelse ($orders as $row)
    <article class="kt-order-card">
        <div>
            <div class="kt-code">
                #{{ $row->order->order_code }} · Table {{ $row->order->table->table_number ?? '-' }}
                <span class="kt-badge {{ $row->status }}">{{ $row->status }}</span>

                @if ($row->queue_type === 'remake')
                    <span class="kt-badge remake">REMAKE</span>
                @endif
            </div>

            <div class="kt-info">
                Queued {{ $row->queued_at?->format('H:i') }} · {{ $row->total_qty }} item
            </div>

            @if ($row->queue_type === 'remake' && $row->reasons->isNotEmpty())
                <div class="kt-info" style="color:#b91c1c;">
                    Alasan: {{ $row->reasons->implode(', ') }}
                </div>
            @endif
        </div>

        <div class="kt-actions">
            @if ($row->status === 'queued')
                <button type="button" class="kt-btn primary btn-prepare-order"
                    data-url="{{ route('dapur.orders.prepare', $row->order->id) }}?type={{ $row->queue_type }}"
                    data-order-code="{{ $row->order->order_code }}">
                    Proses
                </button>
            @else
                <a href="{{ route('dapur.orders.show', $row->order->id) }}" class="kt-btn outline">
                    Lihat Detail
                </a>
            @endif
        </div>
    </article>
@empty
    <div class="kt-empty">
        <strong>No kitchen queue yet.</strong>
        <span>Paid orders will appear here automatically.</span>
    </div>
@endforelse
