<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @include('components.style')

    @php
        $kasirUser = auth()->user();
        $kasirUser?->loadMissing('shift');
        $kasirShift = $kasirUser?->shift;
        $shiftEndsAt = $kasirShift && $kasirShift->isActiveAt() ? $kasirShift->endDateTimeFrom() : null;
    @endphp

    <link rel="stylesheet" href="{{ asset('kasir/css/dashboard.css') }}">
</head>
<body class="kasir-dashboard">
    <main class="ks-shell">
        <div class="ks-page">
            <header class="ks-header">
                <div class="ks-title-wrap">
                    <div class="ks-icon-box" aria-hidden="true">
                        <i class="fe fe-credit-card"></i>
                    </div>
                    <div>
                        <h1 class="ks-title">Dashboard Kasir</h1>
                        <p class="ks-subtitle">Kelola pesanan dan pembayaran dari satu tempat.</p>
                    </div>
                </div>

                <div class="ks-user-panel">
                    <div class="ks-user">
                        <div class="ks-avatar" aria-hidden="true">
                            <i class="fe fe-user"></i>
                        </div>
                        <div>
                            <div class="ks-user-label">Staf masuk</div>
                            <div class="ks-user-name">{{ $kasirUser->name }}</div>
                        </div>
                    </div>

                    <div class="ks-shift-pill">
                        <i class="fe fe-clock"></i>
                        @if($kasirShift)
                            <span>{{ $kasirShift->name }} {{ substr($kasirShift->start_time, 0, 5) }} - {{ substr($kasirShift->end_time, 0, 5) }}</span>
                        @else
                            <span>Shift belum diatur</span>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="mb-0" id="kasirLogoutForm">
                        @csrf
                        <button type="submit" class="ks-logout">
                            <i class="fe fe-log-out"></i>
                            <span>Logout</span>
                        </button>
                    </form>
                </div>
            </header>

            <section class="ks-stats" aria-label="Ringkasan kasir">
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
            </section>

            <section class="ks-main-grid">
                <div class="ks-panel">
                    <div class="ks-panel-header">
                        <h2 class="ks-panel-title">
                            <i class="fe fe-list"></i>
                            <span>Pesanan Terbaru</span>
                        </h2>
                        <span class="ks-panel-meta">Antrian hari ini</span>
                    </div>

                    <div class="ks-orders">
                        @forelse ($orders as $order)
                            @php
                                $isCash = $order->payment_method === 'cash';
                                $isPaid = $order->payment_status === 'paid';
                                $isCashWaiting = $isCash && !$isPaid;
                                $paymentMethod = $order->payment_method ?? 'cashless';
                                $paymentStatus = $order->payment_status;
                            @endphp

                            <div
                                class="ks-order"
                                data-payment-method="{{ $paymentMethod }}"
                                data-payment-status="{{ $paymentStatus }}"
                                data-order-status="{{ $order->status }}"
                            >
                                <div>
                                    <div class="ks-order-code">
                                        #{{ $order->order_code }} - Meja {{ $order->table->table_number ?? '-' }}
                                    </div>

                                    <div class="ks-order-info ks-status {{ $isPaid ? 'done' : 'wait' }}">
                                        <span class="ks-status-dot"></span>
                                        <span>
                                            @if ($isCashWaiting)
                                                Waiting for cash payment
                                            @elseif ($isPaid)
                                                Payment completed
                                            @else
                                                Waiting for online payment
                                            @endif
                                        </span>
                                    </div>

                                    <div class="ks-order-info">
                                        {{ strtoupper($order->payment_method ?? 'cashless') }}
                                        · Rp{{ number_format($order->grand_total, 0, ',', '.') }}
                                        · {{ $order->created_at->format('H:i') }}
                                    </div>
                                </div>

                                @php
                                    $itemsForModal = $order->orderItems->map(function ($item) {
                                        return [
                                            'name' => $item->menuItem->name ?? 'Menu',
                                            'quantity' => $item->quantity,
                                            'notes' => $item->notes,
                                            'subtotal' => (int) $item->subtotal,
                                        ];
                                    })->values();
                                @endphp
                                <div class="ks-actions-row">
                                    <button
                                        type="button"
                                        class="ks-small-btn btn-order-detail"
                                        data-order-code="{{ $order->order_code }}"
                                        data-table="{{ $order->table->table_number ?? '-' }}"
                                        data-payment-method="{{ strtoupper($order->payment_method ?? 'CASHLESS') }}"
                                        data-payment-status="{{ strtoupper($order->payment_status) }}"
                                        data-cashier="{{ $order->payment->processedBy->name ?? 'Online Payment' }}"
                                        data-subtotal="{{ (int) $order->subtotal }}"
                                        data-discount="{{ (int) $order->discount_total }}"
                                        data-grand-total="{{ (int) $order->grand_total }}"
                                        data-amount-paid="{{ (int) optional($order->payment)->amount_paid }}"
                                        data-change-amount="{{ (int) optional($order->payment)->change_amount }}"
                                        data-items='@json($itemsForModal)'
                                    >
                                        Detail
                                    </button>

                                    @php
                                        $rejectItemsForModal = $order->orderItems->map(function ($item) {
                                            return [
                                                'id' => $item->id,
                                                'name' => $item->menuItem->name ?? 'Menu',
                                                'quantity' => (int) $item->quantity,
                                                'notes' => $item->notes,
                                                'subtotal' => (int) $item->subtotal,
                                                'unit_price' => (int) round($item->subtotal / max(1, $item->quantity)),
                                                'status' => $item->status,
                                                'reject_count' => $item->rejectItems->count(),
                                            ];
                                        })->values();
                                    @endphp
                                    @if ($order->payment_status === 'paid')
                                        <button
                                            type="button"
                                            class="ks-small-btn btn-reject-items" 
                                            style="background:#dc2626;border-color:#dc2626;color:#ffffff;"
                                            data-reject-url="{{ route('kasir.orders.reject-items', $order->id) }}"
                                            data-order-code="{{ $order->order_code }}"
                                            data-items='@json($rejectItemsForModal)'
                                        >
                                            Reject
                                        </button>
                                    @endif

                                    @if ($isCashWaiting)
                                        <button
                                            type="button"
                                            class="ks-small-btn primary btn-pay-cash"
                                            data-pay-url="{{ route('kasir.orders.pay-cash', $order->id) }}"
                                            data-total="{{ (int) $order->grand_total }}"
                                            data-order-code="{{ $order->order_code }}"
                                        >
                                            Pay
                                        </button>
                                    @elseif ($isPaid)
                                        <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank" class="ks-small-btn primary">
                                            Print
                                        </a>
                                    @endif
                                </div>
                            </div>

                            <div id="filterEmptyState" class="ks-filter-empty text-center" style="display: none;">
                                <div class="ks-filter-empty-icon">
                                    <i class="fe fe-search"></i>
                                </div>
                                <strong>No orders found</strong>
                                <p id="filterEmptyText">No orders match this filter.</p>
                            </div>
                        @empty
                            <div class="p-4 text-center text-muted">
                                No orders for this shift yet.
                            </div>
                        @endforelse
                    </div>
                </div>

                <aside class="ks-panel">
                    <div class="ks-panel-header">
                        <h2 class="ks-panel-title">
                            <i class="fe fe-zap"></i>
                            <span>Quick Filters</span>
                        </h2>
                    </div>

                    <div class="ks-quick-list">
                        <button type="button" class="ks-quick-action js-filter-order active" data-filter="all">
                            <i class="fe fe-clipboard"></i>
                            <span>All Orders</span>
                        </button>

                        <button type="button" class="ks-quick-action js-filter-order" data-filter="need_payment">
                            <i class="fe fe-credit-card"></i>
                            <span>Need Payment</span>
                        </button>

                        <button type="button" class="ks-quick-action js-filter-order" data-filter="cash">
                            <i class="fe fe-dollar-sign"></i>
                            <span>Cash Orders</span>
                        </button>

                        <button type="button" class="ks-quick-action js-filter-order" data-filter="cashless">
                            <i class="fe fe-wifi"></i>
                            <span>Cashless Orders</span>
                        </button>

                        <button type="button" class="ks-quick-action js-filter-order" data-filter="paid">
                            <i class="fe fe-check-circle"></i>
                            <span>Paid Orders</span>
                        </button>
                    </div>
                </aside>
            </section>
        </div>
    </main>

    @if($shiftEndsAt)
        <script>
            (function () {
                const logoutForm = document.getElementById('kasirLogoutForm');
                const shiftEndsAt = new Date(@json($shiftEndsAt->toIso8601String())).getTime();
                const delay = shiftEndsAt - Date.now();

                if (!logoutForm || delay <= 0) {
                    return;
                }

                window.setTimeout(function () {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'info',
                            title: 'Shift selesai',
                            text: 'Anda akan keluar otomatis dari halaman kasir.',
                            confirmButtonText: 'OK'
                        }).then(function () {
                            logoutForm.submit();
                        });
                    } else {
                        logoutForm.submit();
                    }
                }, delay);
            })();

            function rupiah(value) {
                return 'Rp' + Number(value || 0).toLocaleString('id-ID');
            }

            document.querySelectorAll('.btn-order-detail').forEach(function (button) {
                button.addEventListener('click', function () {
                    const items = JSON.parse(this.dataset.items || '[]');
                    const subtotal = Number(this.dataset.subtotal || 0);
                    const discount = Number(this.dataset.discount || 0);
                    const grandTotal = Number(this.dataset.grandTotal || 0);
                    const amountPaid = Number(this.dataset.amountPaid || 0);
                    const changeAmount = Number(this.dataset.changeAmount || 0);

                    const itemRows = items.map(function (item) {
                        return `
                            <div style="display:flex;justify-content:space-between;gap:12px;padding:8px 0;border-bottom:1px solid #eee;">
                                <div>
                                    <strong>${item.quantity}x ${item.name}</strong>
                                    ${item.notes ? `<div style="font-size:12px;color:#6b7280;">${item.notes}</div>` : ''}
                                </div>
                                <div style="white-space:nowrap;">${rupiah(item.subtotal)}</div>
                            </div>
                        `;
                    }).join('');

                    Swal.fire({
                        title: 'Order Detail',
                        width: 520,
                        html: `
                            <div style="text-align:left;font-size:14px;">
                                <!-- Header info -->
                                <div style="background:#f8faff;border:1px solid #e0e7ff;border-radius:10px;padding:12px 14px;margin-bottom:14px;display:grid;grid-template-columns:1fr 1fr;gap:6px;">
                                    <div><span style="color:#6b7280;font-size:12px;">Order</span><div style="font-weight:700;">${this.dataset.orderCode}</div></div>
                                    <div><span style="color:#6b7280;font-size:12px;">Table</span><div style="font-weight:700;">${this.dataset.table}</div></div>
                                    <div><span style="color:#6b7280;font-size:12px;">Payment</span><div style="font-weight:700;">${this.dataset.paymentMethod}</div></div>
                                    <div><span style="color:#6b7280;font-size:12px;">Cashier</span><div style="font-weight:700;">${this.dataset.cashier}</div></div>
                                </div>

                                <!-- Status badge -->
                                <div style="margin-bottom:12px;">
                                    <span style="background:${this.dataset.paymentStatus==='PAID'?'#dcfce7':'#fef9c3'};color:${this.dataset.paymentStatus==='PAID'?'#16a34a':'#ca8a04'};padding:4px 10px;border-radius:20px;font-size:12px;font-weight:700;">
                                        ${this.dataset.paymentStatus}
                                    </span>
                                </div>

                                <!-- Items -->
                                <div style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;margin-bottom:14px;padding: 20px;">
                                    <div style="background:#f9fafb;padding:8px 12px;font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.05em;">Items</div>
                                    ${itemRows}
                                </div>

                                <!-- Totals -->
                                <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:10px;padding:12px 14px;">
                                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;color:#6b7280;"><span>Subtotal</span><span>${rupiah(subtotal)}</span></div>
                                    <div style="display:flex;justify-content:space-between;margin-bottom:6px;color:#dc2626;"><span>Discount</span><span>-${rupiah(discount)}</span></div>
                                    <div style="display:flex;justify-content:space-between;font-size:16px;font-weight:800;padding-top:8px;border-top:1px solid #e5e7eb;margin-top:4px;"><span>Total</span><span>${rupiah(grandTotal)}</span></div>
                                    ${amountPaid ? `
                                    <div style="margin-top:10px;padding-top:8px;border-top:1px dashed #e5e7eb;">
                                        <div style="display:flex;justify-content:space-between;color:#6b7280;margin-bottom:4px;"><span>Cash Received</span><span>${rupiah(amountPaid)}</span></div>
                                        <div style="display:flex;justify-content:space-between;color:#6b7280;"><span>Change</span><span>${rupiah(changeAmount)}</span></div>
                                    </div>` : ''}
                                </div>
                            </div>
                        `,
                        confirmButtonText: 'Close',
                        confirmButtonColor: '#2563eb',
                    });
                });
            });

            document.querySelectorAll('.btn-pay-cash').forEach(function (button) {
                button.addEventListener('click', function () {
                    const payUrl = this.dataset.payUrl;
                    const total = Number(this.dataset.total);
                    const orderCode = this.dataset.orderCode;

                    Swal.fire({
                        title: 'Cash Payment',
                        html: `
                            <div style="text-align:left">
                                <div style="margin-bottom:8px"><strong>Order:</strong> ${orderCode}</div>
                                <div style="margin-bottom:12px"><strong>Total:</strong> ${rupiah(total)}</div>

                                <label style="font-size:13px;font-weight:700">Cash Received</label>
                                <input id="cashReceived" class="swal2-input" type="number" min="${total}" placeholder="Enter cash amount">

                                <div style="margin-top:10px">
                                    <strong>Change:</strong> <span id="cashChange">Rp0</span>
                                </div>
                            </div>
                        `,
                        showCancelButton: true,
                        confirmButtonText: 'Complete Payment',
                        cancelButtonText: 'Cancel',
                        didOpen: function () {
                            const input = document.getElementById('cashReceived');
                            const changeText = document.getElementById('cashChange');

                            input.addEventListener('input', function () {
                                const received = Number(input.value || 0);
                                const change = Math.max(0, received - total);
                                changeText.textContent = rupiah(change);
                            });
                        },
                        preConfirm: function () {
                            const received = Number(document.getElementById('cashReceived').value || 0);

                            if (received < total) {
                                Swal.showValidationMessage('Cash received must be equal to or greater than the total.');
                                return false;
                            }

                            return received;
                        }
                    }).then(async function (result) {
                        if (!result.isConfirmed) {
                            return;
                        }

                        try {
                            const response = await fetch(payUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify({
                                    amount_paid: result.value
                                })
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                Swal.fire('Failed', data.message || 'Payment failed.', 'error');
                                return;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Payment Completed',
                                text: 'Change: ' + rupiah(data.change_amount),
                            }).then(function () {
                                window.location.reload();
                            });
                        } catch (error) {
                            Swal.fire('Failed', 'Connection error. Please try again.', 'error');
                        }
                    });
                });
            });

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }
            document.querySelectorAll('.btn-reject-items').forEach(function (button) {
                button.addEventListener('click', function () {
                    const rejectUrl = this.dataset.rejectUrl;
                    const orderCode = this.dataset.orderCode;
                    const items = JSON.parse(this.dataset.items || '[]');

                    if (!items.length) {
                        Swal.fire({
                            icon: 'info',
                            title: 'No items',
                            text: 'There are no items available to reject.',
                            confirmButtonColor: '#2563eb'
                        });
                        return;
                    }

                    const itemRows = items.map(function (item) {
                        const itemId = Number(item.id);
                        const itemQty = Number(item.quantity || 1);
                        const unitPrice = Number(item.unit_price || (Number(item.subtotal || 0) / Math.max(1, itemQty)));
                        const itemName = escapeHtml(item.name || 'Menu');
                        const itemNotes = escapeHtml(item.notes || '');
                        const rejectCount = Number(item.reject_count || 0);

                        const noteHtml = itemNotes
                            ? `<div style="font-size:12px;color:#9ca3af;margin-top:3px;">📝 ${itemNotes}</div>`
                            : '';

                        const rejectBadge = rejectCount > 0
                            ? `<div style="display:inline-flex;align-items:center;gap:4px;font-size:11px;font-weight:700;color:#b91c1c;background:#fef2f2;border:1px solid #fecaca;border-radius:20px;padding:2px 8px;margin-top:4px;">
                                ⚠ Rejected ${rejectCount}x before
                            </div>`
                            : '';

                        return `
                            <div style="padding:12px;border-bottom:1px solid #f3f4f6;transition:background .15s;" onmouseover="this.style.background='#f9fafb'" onmouseout="this.style.background=''">
                                <label style="display:flex;gap:12px;align-items:flex-start;cursor:pointer;margin:0;">
                                    <div style="margin-top:3px;">
                                        <input
                                            type="checkbox"
                                            class="reject-item-checkbox"
                                            value="${itemId}"
                                            data-max="${itemQty}"
                                            style="width:16px;height:16px;cursor:pointer;accent-color:#2563eb;"
                                        >
                                    </div>
                                    <div style="flex:1;">
                                        <div style="display:flex;justify-content:space-between;align-items:flex-start;gap:8px;">
                                            <div style="font-weight:700;font-size:14px;color:#111827;">
                                                <span style="background:#eff6ff;color:#1d4ed8;border-radius:4px;padding:1px 6px;font-size:12px;margin-right:6px;">${itemQty}x</span>${itemName}
                                            </div>
                                            <div style="font-size:13px;font-weight:700;color:#374151;white-space:nowrap;">
                                                Rp${unitPrice.toLocaleString('id-ID')}/pcs
                                            </div>
                                        </div>
                                        ${noteHtml}
                                        ${rejectBadge}
                                        <div style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                                            <span style="font-size:12px;font-weight:700;color:#6b7280;">Qty reject</span>
                                            <input
                                                type="number"
                                                class="reject-qty-input"
                                                data-item-id="${itemId}"
                                                min="1"
                                                max="${itemQty}"
                                                value="1"
                                                disabled
                                                style="width:72px;height:32px;border:1px solid #d1d5db;border-radius:8px;padding:0 8px;font-size:13px;font-weight:700;text-align:center;color:#111827;background:#f9fafb;"
                                            >
                                            <span style="font-size:12px;color:#9ca3af;">maks ${itemQty}</span>
                                        </div>
                                    </div>
                                </label>
                            </div>
                        `;
                    }).join('');

                    Swal.fire({
                        title: 'Reject Order Items',
                        width: 520,
                        showCancelButton: true,
                        confirmButtonText: 'Send Back to Kitchen',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#dc2626',
                        cancelButtonColor: '#6b7280',
                        html: `
                            <div style="text-align:left;font-size:14px;">

                                <!-- Order badge -->
                                <div style="display:inline-flex;align-items:center;gap:6px;background:#fef2f2;border:1px solid #fecaca;border-radius:8px;padding:6px 12px;margin-bottom:14px;">
                                    <span style="font-size:12px;color:#9ca3af;">Order</span>
                                    <span style="font-weight:800;color:#b91c1c;font-size:13px;">${escapeHtml(orderCode)}</span>
                                </div>

                                <!-- Items list -->
                                <div style="border:1px solid #e5e7eb;border-radius:10px;overflow:hidden;margin-bottom:14px;">
                                    <div style="background:#f9fafb;padding:8px 12px;border-bottom:1px solid #e5e7eb;">
                                        <span style="font-size:11px;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.06em;">Select items to reject</span>
                                    </div>
                                    <div style="max-height:260px;overflow-y:auto;">
                                        ${itemRows}
                                    </div>
                                </div>

                                <!-- Reason -->
                                <div style="background:#fffbeb;border:1px solid a;border-radius:10px;padding:12px 14px;">
                                    <label style="font-size:12px;font-weight:700;color:#92400e;display:block;margin-bottom:6px;">Reject reason (required)</label>
                                    <textarea
                                        id="rejectReason"
                                        class="swal2-textarea"
                                        placeholder="e.g. Wrong item, customer complaint, spilled drink…"
                                        style="height:80px;margin:0;width:100%;border-color:#fcd34d;font-size:13px;box-sizing:border-box;"
                                    ></textarea>
                                </div>
                            </div>
                        `,
                        didOpen: function () {
                            document.querySelectorAll('.reject-item-checkbox').forEach(function (checkbox) {
                                checkbox.addEventListener('change', function () {
                                    const itemId = this.value;
                                    const qtyInput = document.querySelector(`.reject-qty-input[data-item-id="${itemId}"]`);
                                    if (!qtyInput) return;
                                    qtyInput.disabled = !this.checked;
                                    qtyInput.style.background = this.checked ? '#fff' : '#f9fafb';
                                    qtyInput.style.borderColor = this.checked ? '#2563eb' : '#d1d5db';
                                    if (this.checked) { qtyInput.focus(); qtyInput.select(); }
                                    else { qtyInput.value = 1; }
                                });
                            });
                        },
                        preConfirm: function () {
                            const selectedItems = [];
                            const checkedBoxes = document.querySelectorAll('.reject-item-checkbox:checked');
                            const reason = document.getElementById('rejectReason').value.trim();

                            if (checkedBoxes.length === 0) {
                                Swal.showValidationMessage('Select at least one item to reject.');
                                return false;
                            }
                            if (!reason) {
                                Swal.showValidationMessage('Enter a reject reason before continuing.');
                                return false;
                            }

                            for (const checkbox of checkedBoxes) {
                                const itemId = Number(checkbox.value);
                                const maxQty = Number(checkbox.dataset.max || 1);
                                const qtyInput = document.querySelector(`.reject-qty-input[data-item-id="${itemId}"]`);
                                const rejectQty = Number(qtyInput?.value || 0);

                                if (!rejectQty || rejectQty < 1) {
                                    Swal.showValidationMessage('Reject quantity must be at least 1.');
                                    return false;
                                }
                                if (rejectQty > maxQty) {
                                    Swal.showValidationMessage('Reject quantity exceeds ordered quantity.');
                                    return false;
                                }

                                selectedItems.push({ order_item_id: itemId, quantity: rejectQty });
                            }

                            return { items: selectedItems, reason: reason };
                        }
                    }).then(async function (result) {
                        if (!result.isConfirmed) return;

                        try {
                            const response = await fetch(rejectUrl, {
                                method: 'POST',
                                headers: {
                                    'Accept': 'application/json',
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                },
                                body: JSON.stringify(result.value)
                            });

                            const data = await response.json();

                            if (!response.ok) {
                                Swal.fire({ icon: 'error', title: 'Failed', text: data.message || 'Failed to reject items.', confirmButtonColor: '#2563eb' });
                                return;
                            }

                            Swal.fire({
                                icon: 'success',
                                title: 'Sent back to kitchen',
                                text: data.message || 'Selected items have been sent back to the kitchen.',
                                confirmButtonColor: '#2563eb'
                            }).then(function () {
                                window.location.reload();
                            });

                        } catch (error) {
                            Swal.fire({ icon: 'error', title: 'Failed', text: 'Connection error. Please try again.', confirmButtonColor: '#2563eb' });
                        }
                    });
                });
            });

            document.querySelectorAll('.js-filter-order').forEach(function (button) {
                button.addEventListener('click', function () {
                    const filter = this.dataset.filter;
                    const filterLabel = this.querySelector('span')?.textContent || 'selected filter';

                    const emptyState = document.getElementById('filterEmptyState');
                    const emptyText = document.getElementById('filterEmptyText');

                    document.querySelectorAll('.js-filter-order').forEach(function (btn) {
                        btn.classList.remove('active');
                    });

                    this.classList.add('active');

                    let visibleCount = 0;

                    document.querySelectorAll('.ks-order').forEach(function (orderCard) {
                        const paymentMethod = orderCard.dataset.paymentMethod;
                        const paymentStatus = orderCard.dataset.paymentStatus;

                        let shouldShow = true;

                        if (filter === 'need_payment') {
                            shouldShow = paymentStatus === 'unpaid';
                        }

                        if (filter === 'cash') {
                            shouldShow = paymentMethod === 'cash';
                        }

                        if (filter === 'cashless') {
                            shouldShow = paymentMethod !== 'cash';
                        }

                        if (filter === 'paid') {
                            shouldShow = paymentStatus === 'paid';
                        }

                        if (filter === 'all') {
                            shouldShow = true;
                        }

                        orderCard.style.display = shouldShow ? '' : 'none';

                        if (shouldShow) {
                            visibleCount++;
                        }
                    });

                    if (emptyState) {
                        emptyState.style.display = visibleCount === 0 ? 'block' : 'none';
                    }

                    if (emptyText) {
                        emptyText.textContent = `There are no orders for "${filterLabel}" in this shift.`;
                    }
                });
            });
        </script>
    @endif
</body>
</html>
