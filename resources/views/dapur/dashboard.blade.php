<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kitchen Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @include('components.style')

    <style>
        body.kitchen-dashboard { min-height: 100vh; background: #f4f7fb; color: #111827; }
        .kt-shell { min-height: 100vh; padding: 24px; }
        .kt-page { width: min(100%, 1180px); margin: 0 auto; }

        .kt-header { display: flex; align-items: center; justify-content: space-between; gap: 18px; margin-bottom: 20px; }
        .kt-title-wrap { display: flex; align-items: center; gap: 12px; }
        .kt-icon-box { width: 44px; height: 44px; border-radius: 10px; background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; display: flex; align-items: center; justify-content: center; }
        .kt-title { font-size: 24px; font-weight: 800; margin: 0 0 3px; }
        .kt-subtitle { color: #6b7280; font-size: 14px; margin: 0; }
        .kt-user-panel { display: flex; align-items: center; gap: 12px; background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 10px 12px; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
        .kt-shift-pill { min-height: 36px; display: inline-flex; align-items: center; gap: 7px; border-radius: 8px; background: #eff6ff; border: 1px solid #bfdbfe; color: #2563eb; padding: 0 12px; font-size: 12px; font-weight: 800; white-space: nowrap; }
        .kt-logout { height: 36px; border: 1px solid #d1d5db; background: #f9fafb; color: #374151; border-radius: 8px; padding: 0 13px; font-size: 13px; font-weight: 800; display: inline-flex; align-items: center; justify-content: center; gap: 7px; }

        .kt-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 14px; margin-bottom: 18px; }
        .kt-stat-card, .kt-panel { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 4px 12px rgba(15,23,42,.04); }
        .kt-stat-card { padding: 17px; }
        .kt-stat-label { font-size: 12px; color: #6b7280; font-weight: 800; }
        .kt-stat-value { font-size: 28px; font-weight: 900; color: #111827; line-height: 1; margin-top: 12px; }

        .kt-panel-header { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 17px 18px; border-bottom: 1px solid #f3f4f6; }
        .kt-panel-title { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 800; margin: 0; }
        .kt-panel-meta { color: #9ca3af; font-size: 12px; white-space: nowrap; }

        .kt-orders { padding: 10px; display: grid; gap: 10px; }

        .kt-order-card {
            display: grid;
            grid-template-columns: minmax(0, 1fr) auto;
            align-items: center;
            gap: 12px;
            padding: 16px;
            border-radius: 10px;
            border: 1px solid #f3f4f6;
            transition: background .15s ease;
        }
        .kt-order-card:hover { background: #f9fafb; }

        .kt-code { display: flex; align-items: center; gap: 8px; font-size: 15px; font-weight: 900; color: #111827; margin-bottom: 6px; }
        .kt-info { color: #6b7280; font-size: 13px; line-height: 1.5; }

        .kt-badge { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 10px; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: .02em; }
        .kt-badge.queued { background: #fef3c7; color: #b45309; }
        .kt-badge.preparing { background: #dbeafe; color: #1d4ed8; }
        .kt-badge.done { background: #dcfce7; color: #15803d; }

        .kt-actions { display: flex; align-items: center; justify-content: flex-end; gap: 8px; }
        .kt-btn {
            min-height: 38px;
            border: 1px solid #d1d5db;
            background: #fff;
            color: #374151;
            border-radius: 8px;
            padding: 0 16px;
            font-size: 13px;
            font-weight: 800;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            text-decoration: none;
            white-space: nowrap;
        }
        .kt-btn.primary { background: #ea580c; border-color: #ea580c; color: #fff; }
        .kt-btn.outline { background: #eff6ff; border-color: #bfdbfe; color: #2563eb; }
        .kt-btn:disabled { opacity: .6; cursor: not-allowed; }

        .kt-empty { padding: 50px 18px; text-align: center; color: #6b7280; }
        .kt-empty strong { display: block; color: #111827; margin-bottom: 5px; font-size: 15px; }
        .kt-badge.remake { background: #fee2e2; color: #b91c1c; }

        @media (max-width: 768px) {
            .kt-shell { padding: 14px; }
            .kt-header { align-items: flex-start; flex-direction: column; }
            .kt-user-panel { width: 100%; justify-content: space-between; }
            .kt-stats { grid-template-columns: 1fr; }
            .kt-order-card { grid-template-columns: 1fr; }
            .kt-actions { justify-content: stretch; }
            .kt-btn { flex: 1; }
        }
    </style>
</head>

<body class="kitchen-dashboard">
    @php
        $dapurUser = auth()->user();
        $dapurUser?->loadMissing('shift');
        $dapurShift = $dapurUser?->shift;
    @endphp

    <main class="kt-shell">
        <div class="kt-page">
            <header class="kt-header">
                <div class="kt-title-wrap">
                    <div class="kt-icon-box"><i class="fe fe-package"></i></div>
                    <div>
                        <h1 class="kt-title">Kitchen Dashboard</h1>
                        <p class="kt-subtitle">Receive, prepare, and complete paid orders.</p>
                    </div>
                </div>

                <div class="kt-user-panel">
                    <div class="kt-shift-pill">
                        <i class="fe fe-clock"></i>
                        @if ($dapurShift)
                            <span>{{ $dapurShift->name }} {{ substr($dapurShift->start_time, 0, 5) }} - {{ substr($dapurShift->end_time, 0, 5) }}</span>
                        @else
                            <span>No shift assigned</span>
                        @endif
                    </div>

                    @include('components.notification-bell')

                    <form method="POST" action="{{ route('logout') }}" class="mb-0">
                        @csrf
                        <button type="submit" class="kt-logout"><i class="fe fe-log-out"></i> Logout</button>
                    </form>
                </div>
            </header>

            <section class="kt-stats">
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
            </section>

            <section class="kt-panel">
                <div class="kt-panel-header">
                    <h2 class="kt-panel-title"><i class="fe fe-list"></i> Kitchen Queue</h2>
                    <span class="kt-panel-meta">Current active shift</span>
                </div>

                <div class="kt-orders">
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
                                    <button
                                        type="button"
                                        class="kt-btn primary btn-prepare-order"
                                        data-url="{{ route('dapur.orders.prepare', $row->order->id) }}?type={{ $row->queue_type }}"
                                        data-order-code="{{ $row->order->order_code }}"
                                    >
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
                </div>
            </section>
        </div>
    </main>

    <script>
        function csrfToken() {
            return document.querySelector('meta[name="csrf-token"]').content;
        }

        function bindKitchenAction(selector, title, confirmText, successTitle) {
            document.querySelectorAll(selector).forEach(function (button) {
                button.addEventListener('click', async function () {
                    const result = await Swal.fire({
                        title: title,
                        text: `Order ${this.dataset.orderCode}`,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: confirmText,
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

                        await Swal.fire(successTitle, data.message, 'success');

                        // langsung pindah ke halaman detail setelah OK
                        window.location.href = data.redirect || window.location.href;
                    } catch (error) {
                        Swal.fire('Failed', 'Connection error. Please try again.', 'error');
                        this.disabled = false;
                        this.textContent = originalText;
                    }
                });
            });
        }

        bindKitchenAction('.btn-prepare-order', 'Mulai Proses Order?', 'Ya, Proses', 'Diproses');
    </script>
</body>
</html>
