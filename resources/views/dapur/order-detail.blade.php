<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Order · Kitchen</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('components.style')

    <style>
        body.kitchen-detail { min-height: 100vh; background: #f4f7fb; color: #111827; }
        .kt-shell { min-height: 100vh; padding: 24px; }
        .kt-page { width: min(100%, 760px); margin: 0 auto; }

        .kt-back { display: inline-flex; align-items: center; gap: 6px; color: #6b7280; font-size: 13px; font-weight: 700; text-decoration: none; margin-bottom: 16px; }
        .kt-back:hover { color: #111827; }

        .kt-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(15,23,42,.04); overflow: hidden; }

        .kt-detail-header { padding: 20px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; }
        .kt-order-code { font-size: 20px; font-weight: 900; margin: 0 0 4px; }
        .kt-order-meta { color: #6b7280; font-size: 13px; }

        .kt-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 5px 12px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .02em; }
        .kt-badge.queued { background: #fef3c7; color: #b45309; }
        .kt-badge.preparing { background: #dbeafe; color: #1d4ed8; }
        .kt-badge.done { background: #dcfce7; color: #15803d; }

        .kt-items { padding: 8px; }
        .kt-item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 16px;
            border-bottom: 1px solid #f3f4f6;
        }
        .kt-item:last-child { border-bottom: none; }
        .kt-item-name { font-size: 15px; font-weight: 800; color: #111827; }
        .kt-item-qty { color: #ea580c; font-weight: 900; }
        .kt-note { margin-top: 5px; font-size: 12px; color: #6b7280; background: #f9fafb; border-radius: 6px; padding: 6px 9px; display: inline-block; }

        .kt-footer { padding: 18px 20px; border-top: 1px solid #f3f4f6; display: flex; justify-content: flex-end; }

        .kt-btn {
            min-height: 42px;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            border-radius: 8px;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }
        .kt-btn.success { background: #16a34a; border-color: #16a34a; color: #fff; }
        .kt-btn:disabled { opacity: .6; cursor: not-allowed; }

        .kt-done-msg { padding: 40px 20px; text-align: center; color: #15803d; font-weight: 800; }

        @media (max-width: 768px) {
            .kt-shell { padding: 14px; }
        }
    </style>
</head>

<body class="kitchen-detail">
    <main class="kt-shell">
        <div class="kt-page">
            <a href="{{ route('dapur.dashboard') }}" class="kt-back">&larr; Kembali ke Dashboard</a>

            <section class="kt-panel">
                <div class="kt-detail-header">
                    <div>
                        <h1 class="kt-order-code">#{{ $order->order_code }}</h1>
                        <div class="kt-order-meta">
                            Table {{ $order->table->table_number ?? '-' }}
                            @if ($order->customer_name)
                                · {{ $order->customer_name }}
                            @endif
                            @if ($order->customer_phone)
                                · {{ $order->customer_phone }}
                            @endif
                        </div>

                        <div class="kt-order-meta" style="margin-top: 3px;">
                            {{ ucfirst($order->payment_method ?? '-') }}
                            ·
                            Rp {{ number_format($order->grand_total, 0, ',', '.') }}
                        </div>
                    </div>
                </div>

                @if (!empty($order->notes))
                    <div style="padding: 12px 20px; background: #fffbeb; border-bottom: 1px solid #f3f4f6;">
                        <strong style="font-size:12px; color:#b45309;">Catatan Order:</strong>
                        <div style="font-size:13px; color:#78350f; margin-top:2px;">{{ $order->notes }}</div>
                    </div>
                @endif

                <div class="kt-items">
                    @foreach ($groups as $group)
                        <section class="kt-panel" style="margin-bottom: 14px;">
                            <div class="kt-detail-header">
                                <div>
                                    <h2 style="margin:0;font-size:15px;">
                                        {{ $group->queue_type === 'remake' ? 'Remake' : 'Pesanan Baru' }}
                                    </h2>
                                </div>
                                <span class="kt-badge {{ $group->status }}">{{ $group->status }}</span>
                            </div>

                            <div class="kt-items">
                                @foreach ($group->items as $queue)
                                    @php $orderItem = $queue->orderItem; @endphp
                                    <div class="kt-item">
                                        <div>
                                            <div class="kt-item-name">
                                                <span class="kt-item-qty">{{ $queue->quantity }}x</span>
                                                {{ $orderItem->menuItem->name ?? 'Menu' }}
                                            </div>
                                            @if (!empty($orderItem->notes))
                                                <div class="kt-note">{{ $orderItem->notes }}</div>
                                            @endif
                                        </div>
                                        <span class="kt-badge {{ $queue->status }}">{{ $queue->status }}</span>
                                    </div>
                                @endforeach
                            </div>

                            @if ($group->status === 'preparing')
                                <div class="kt-footer">
                                    <button
                                        type="button"
                                        class="kt-btn success btn-done-order"
                                        data-url="{{ route('dapur.orders.done', $order->id) }}?type={{ $group->queue_type }}"
                                        data-order-code="{{ $order->order_code }}"
                                    >
                                        Tandai Selesai
                                    </button>
                                </div>
                            @elseif ($group->status === 'done')
                                <div class="kt-done-msg">Selesai diproses</div>
                            @endif
                        </section>
                    @endforeach
                </div>
            </section>
        </div>
    </main>

    <script>
        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        document.querySelectorAll('.btn-done-order').forEach(function (btn) {
            btn.addEventListener('click', async function () {
                const result = await Swal.fire({
                    title: 'Tandai selesai?',
                    text: `Order ${this.dataset.orderCode}`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Selesai',
                    cancelButtonText: 'Cancel'
                });

                if (!result.isConfirmed) return;

                this.disabled = true;
                const originalText = this.textContent;
                this.textContent = 'Processing...';

                try {
                    const response = await fetch(this.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrfToken(),
                        },
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        Swal.fire('Failed', data.message || 'Action failed.', 'error');
                        this.disabled = false;
                        this.textContent = originalText;
                        return;
                    }

                    await Swal.fire('Selesai', data.message, 'success');
                    window.location.href = data.redirect || '{{ route('dapur.dashboard') }}';
                } catch (error) {
                    Swal.fire('Failed', 'Connection error. Please try again.', 'error');
                    this.disabled = false;
                    this.textContent = originalText;
                }
            });
        });
    </script>
</body>
</html>