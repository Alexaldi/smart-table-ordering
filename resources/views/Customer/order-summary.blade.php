<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Status</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --accent: #d7a46f;
            --accent-dark: #9b6b3f;
            --ink: #131820;
            --muted: #7d838c;
            --line: #e7e1dc;
            --soft: #faf8f6;
            --success: #18a84a;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #f3f1ef;
            color: var(--ink);
            font-family: 'Poppins', sans-serif;
        }

        .page {
            width: min(100%, 480px);
            min-height: 100vh;
            margin: 0 auto;
            background: #fff;
            border-left: 1px solid var(--line);
            border-right: 1px solid var(--line);
        }

        .topbar {
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-top: 3px solid #5a4f55;
            border-bottom: 1px solid var(--line);
            box-shadow: 0 3px 12px rgba(0, 0, 0, .05);
            background: #fff;
            position: sticky;
            top: 0;
            z-index: 5;
        }

        .topbar h1 {
            font-size: 16px;
            font-weight: 800;
            margin: 0;
        }

        .content {
            padding: 14px 16px 88px;
        }

        .order-type {
            border: 1.5px solid var(--accent);
            border-radius: 8px;
            padding: 10px 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 13px;
            margin-bottom: 22px;
        }

        .order-type strong {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            font-size: 13px;
        }

        .check {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: var(--success);
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
        }

        .status-card {
            background: linear-gradient(180deg, #fffaf4 0%, #fff 100%);
            border: 1.5px solid #ead4bb;
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 18px;
            box-shadow: 0 10px 22px rgba(117, 75, 35, .08);
        }

        .status-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 14px;
            margin-bottom: 12px;
        }

        .status-kicker {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .status-title {
            margin: 0;
            color: var(--ink);
            font-size: 18px;
            line-height: 1.25;
            font-weight: 800;
        }

        .status-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #fff;
            color: var(--accent-dark);
            border: 1px solid #ead4bb;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            font-size: 20px;
        }

        .status-message {
            color: #5f6670;
            font-size: 13px;
            line-height: 1.6;
            margin: 0 0 14px;
        }

        .status-code {
            border: 1px dashed #d8b78f;
            border-radius: 10px;
            background: #fff;
            padding: 12px;
            display: grid;
            grid-template-columns: 72px minmax(0, 1fr) 36px;
            align-items: center;
            gap: 10px;
        }

        .status-code span {
            color: var(--muted);
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .status-code strong {
            color: var(--ink);
            font-size: 13px;
            text-align: right;
            overflow-wrap: anywhere;
            line-height: 1.25;
        }

        .copy-code-btn {
            border: 0;
            border-radius: 8px;
            background: #fff4e7;
            color: var(--accent-dark);
            width: 34px;
            height: 34px;
            min-width: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex: 0 0 auto;
            cursor: pointer;
        }

        .copy-code-btn.copied {
            background: #e8f9ee;
            color: #16763a;
        }

        .timeline {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 8px;
            margin-bottom: 18px;
        }

        .timeline-step {
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 8px;
            color: var(--muted);
            background: #fff;
            min-width: 0;
            min-height: 58px;
        }

        .timeline-dot {
            width: 18px;
            height: 18px;
            border-radius: 999px;
            border: 2px solid #d7dde4;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 7px;
        }

        .timeline-step:not(.done) .timeline-dot i {
            display: none;
        }

        .timeline-step.done {
            border-color: #bfe8cd;
            background: #f4fff8;
            color: #16763a;
        }

        .timeline-step.done .timeline-dot {
            background: var(--success);
            border-color: var(--success);
            color: #fff;
        }

        .timeline-step.current {
            border-color: #e6be88;
            background: #fff8ee;
            color: var(--accent-dark);
        }

        .timeline-step.current .timeline-dot {
            background: #fff;
            border-color: #d7a46f;
            color: var(--accent-dark);
        }

        .timeline-label {
            font-size: 11px;
            font-weight: 800;
            line-height: 1.3;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
            margin-bottom: 22px;
        }

        .meta-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 3px;
        }

        .meta-value {
            font-size: 13px;
            font-weight: 700;
            line-height: 1.35;
        }

        .text-end {
            text-align: right;
        }

        .order-code {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: var(--ink);
        }

        .order-code i {
            color: var(--accent);
        }

        .location-row {
            display: grid;
            grid-template-columns: 22px 1fr;
            gap: 10px;
            margin-bottom: 18px;
        }

        .location-row i {
            color: var(--accent);
            font-size: 20px;
            line-height: 1;
            margin-top: 2px;
        }

        .section {
            border-top: 1px solid var(--line);
            padding: 16px 0;
        }

        .section-title {
            font-size: 15px;
            font-weight: 800;
            margin: 0 0 14px;
        }

        .item {
            display: grid;
            grid-template-columns: 1fr auto;
            gap: 14px;
            margin-bottom: 18px;
        }

        .item:last-child {
            margin-bottom: 0;
        }

        .item-name {
            font-size: 13px;
            font-weight: 500;
            text-transform: uppercase;
        }

        .item-name b {
            font-weight: 800;
            margin-right: 5px;
        }

        .item-note {
            color: var(--muted);
            font-size: 12px;
            margin-left: 24px;
            margin-top: 2px;
            line-height: 1.35;
        }

        .item-price {
            font-size: 13px;
            white-space: nowrap;
        }

        .total-box {
            border-top: 1px solid var(--line);
            border-bottom: 1px solid var(--line);
            padding: 16px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .total-row .muted {
            color: #a5abb3;
        }

        .payment-method {
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
        }

        .grand-total {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            margin-top: 20px;
            font-size: 14px;
            font-weight: 800;
        }

        .grand-total .amount {
            color: var(--accent-dark);
            font-size: 16px;
        }

        .spacer-box {
            height: 66px;
            border-bottom: 1px solid var(--line);
        }

        .bottom-actions {
            position: fixed;
            left: 50%;
            bottom: 0;
            transform: translateX(-50%);
            width: min(100%, 480px);
            background: #fff;
            border-top: 1px solid var(--line);
            padding: 14px 16px;
        }

        .customer-toast {
            position: fixed;
            left: 50%;
            bottom: 78px;
            transform: translate(-50%, 18px);
            width: min(calc(100% - 32px), 448px);
            background: #111827;
            color: #fff;
            border-radius: 14px;
            box-shadow: 0 18px 36px rgba(17, 24, 39, .26);
            display: grid;
            grid-template-columns: 38px minmax(0, 1fr);
            gap: 12px;
            align-items: center;
            padding: 12px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .2s ease, transform .2s ease;
            z-index: 20;
        }

        .customer-toast.show {
            opacity: 1;
            transform: translate(-50%, 0);
        }

        .customer-toast-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            background: rgba(215, 164, 111, .18);
            color: #f4c38e;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 19px;
        }

        .customer-toast-title {
            font-size: 13px;
            font-weight: 800;
            margin-bottom: 2px;
        }

        .customer-toast-message {
            color: #d1d5db;
            font-size: 12px;
            line-height: 1.45;
        }

        .btn-action {
            width: 100%;
            height: 46px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            font-weight: 800;
            font-size: 13px;
        }

        .btn-outline {
            border: 1.5px solid var(--accent);
            color: var(--accent-dark);
            background: #fff;
        }

        .btn-main {
            background: var(--accent);
            color: #fff;
        }

        .btn-main:hover,
        .btn-outline:hover {
            color: inherit;
        }

        .btn-main:hover {
            color: #fff;
            background: #c9945f;
        }

        @media (min-width: 768px) {
            .page {
                margin-top: 18px;
                margin-bottom: 18px;
                min-height: calc(100vh - 36px);
                border-radius: 10px;
                overflow: hidden;
            }

            .bottom-actions {
                bottom: 18px;
                border-left: 1px solid var(--line);
                border-right: 1px solid var(--line);
            }

            .customer-toast {
                bottom: 96px;
            }
        }

        @media (max-width: 430px) {
            .timeline {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .timeline-step {
                display: grid;
                grid-template-columns: 22px minmax(0, 1fr);
                align-items: center;
                gap: 8px;
                padding: 10px;
                min-height: 50px;
            }

            .timeline-dot {
                margin-bottom: 0;
            }
        }

        @media (max-width: 360px) {
            .status-code {
                grid-template-columns: 1fr 36px;
            }

            .status-code span {
                grid-column: 1 / -1;
            }

            .status-code strong {
                text-align: left;
            }
        }
    </style>
</head>

<body>
    @php
        $statusPayload = $statusPayload ?? [
            'stage' => $order->payment_status === 'paid' ? 'paid' : 'waiting_payment',
            'label' => $order->payment_status === 'paid' ? 'Pembayaran Diterima' : 'Menunggu Pembayaran',
            'message' => $order->payment_status === 'paid'
                ? 'Pembayaran sudah dikonfirmasi. Pesanan akan segera diproses.'
                : 'Tunjukkan kode order ini ke kasir. Pesanan akan diproses setelah pembayaran dikonfirmasi.',
            'steps' => [
                'created' => 'done',
                'paid' => $order->payment_status === 'paid' ? 'done' : 'current',
                'preparing' => 'pending',
                'ready' => $order->status === 'ready' ? 'done' : 'pending',
            ],
        ];
        $stepClass = fn ($step) => in_array($statusPayload['steps'][$step] ?? 'pending', ['done', 'current'], true)
            ? $statusPayload['steps'][$step]
            : '';
    @endphp
    <main class="page">
        <header class="topbar">
            <h1>Order Status</h1>
        </header>

        <div class="content">
            <div class="order-type">
                <span>Order Type</span>
                <strong>
                    Dine In
                    <span class="check"><i class="bi bi-check-lg"></i></span>
                </strong>
            </div>

            <section class="status-card" aria-live="polite">
                <div class="status-top">
                    <div>
                        <div class="status-kicker">Status Pesanan</div>
                        <h2 class="status-title" id="orderStatusLabel">{{ $statusPayload['label'] }}</h2>
                    </div>
                    <div class="status-icon" id="orderStatusIcon"><i class="bi bi-receipt-cutoff"></i></div>
                </div>
                <p class="status-message" id="orderStatusMessage">{{ $statusPayload['message'] }}</p>
                <div class="status-code">
                    <span>Kode order</span>
                    <strong id="orderCodeText">{{ $order->order_code }}</strong>
                    <button class="copy-code-btn" id="copyOrderCode" type="button" aria-label="Copy order code">
                        <i class="bi bi-copy"></i>
                    </button>
                </div>
            </section>

            <section class="timeline" aria-label="Order progress">
                <div class="timeline-step {{ $stepClass('created') }}" data-step="created">
                    <span class="timeline-dot"><i class="bi bi-check-lg"></i></span>
                    <div class="timeline-label">Order Dibuat</div>
                </div>
                <div class="timeline-step {{ $stepClass('paid') }}" data-step="paid">
                    <span class="timeline-dot"><i class="bi bi-check-lg"></i></span>
                    <div class="timeline-label">Pembayaran</div>
                </div>
                <div class="timeline-step {{ $stepClass('preparing') }}" data-step="preparing">
                    <span class="timeline-dot"><i class="bi bi-check-lg"></i></span>
                    <div class="timeline-label">Dapur Proses</div>
                </div>
                <div class="timeline-step {{ $stepClass('ready') }}" data-step="ready">
                    <span class="timeline-dot"><i class="bi bi-check-lg"></i></span>
                    <div class="timeline-label">Siap</div>
                </div>
            </section>

            <div class="meta-grid">
                <div>
                    <div class="meta-label">Date</div>
                    <div class="meta-value">{{ $order->created_at->format('d M Y, H:i') }}</div>
                </div>

                <div class="text-end">
                    <div class="meta-label">Order Number</div>
                    <div class="meta-value order-code">
                        <i class="bi bi-files"></i>
                        {{ $order->order_code }}
                    </div>
                </div>
            </div>

            <div class="location-row">
                <i class="bi bi-shop"></i>
                <div>
                    <div class="meta-label">Outlet Location</div>
                    <div class="meta-value">Meja Terakhir Coffee</div>
                </div>
            </div>

            <div class="location-row">
                <i class="bi bi-cup-hot"></i>
                <div>
                    <div class="meta-label">Table Number</div>
                    <div class="meta-value">{{ $table->table_number ?? $table->name ?? '-' }}</div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-3" id="countdown-card" style="display: none;">
                <div class="card-body text-center">
                    <small class="text-muted">Estimasi Pesanan Siap</small>
                    <h2 id="countdown" class="fw-bold text-warning mb-0 mt-2">--:--</h2>
                </div>
            </div>

            <section class="section">
                <h2 class="section-title">Ordered Items</h2>

                @foreach ($order->orderItems as $item)
                    <div class="item">
                        <div>
                            <div class="item-name">
                                <b>{{ $item->quantity }}x</b> {{ $item->menuItem->name ?? 'Menu' }}
                            </div>

                            @if (!empty($item->notes))
                                <div class="item-note">{{ $item->notes }}</div>
                            @endif
                        </div>

                        <div class="item-price">
                            Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="total-box">
                <div class="total-row">
                    <span>Subtotal <span class="muted">({{ $order->orderItems->sum('quantity') }} item)</span></span>
                    <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
                </div>

                <div class="total-row">
                    <span>Diskon</span>
                    <span>-Rp{{ number_format($order->discount_total, 0, ',', '.') }}</span>
                </div>

                <div class="total-row">
                    <span>Payment Method</span>
                    <span class="payment-method">{{ strtoupper($order->payment_method ?? '-') }}</span>
                </div>

                <div class="grand-total">
                    <span>Total</span>
                    <span class="amount">Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
                </div>
            </section>

            <div class="spacer-box"></div>
        </div>

        <div class="bottom-actions">
            <a href="{{ route('customer-menu.index', ['token' => $token]) }}" class="btn-action btn-main">
                New Order
            </a>
        </div>
    </main>
    <div class="customer-toast" id="customerStatusToast" role="status" aria-live="polite">
        <div class="customer-toast-icon" id="customerToastIcon"><i class="bi bi-bell"></i></div>
        <div>
            <div class="customer-toast-title" id="customerToastTitle">Status diperbarui</div>
            <div class="customer-toast-message" id="customerToastMessage">Pesanan kamu punya update baru.</div>
        </div>
    </div>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const card = document.getElementById("countdown-card");
        const countdownEl = document.getElementById("countdown");
        const statusLabel = document.getElementById("orderStatusLabel");
        const statusMessage = document.getElementById("orderStatusMessage");
        const statusIcon = document.getElementById("orderStatusIcon");
        const orderCodeText = document.getElementById("orderCodeText");
        const copyOrderCode = document.getElementById("copyOrderCode");
        const toast = document.getElementById("customerStatusToast");
        const toastIcon = document.getElementById("customerToastIcon");
        const toastTitle = document.getElementById("customerToastTitle");
        const toastMessage = document.getElementById("customerToastMessage");
        let finishTime = null;
        let tickInterval = null;
        let lastStage = @json($statusPayload['stage']);
        let toastTimer = null;
        let audioContext = null;

        const statusIcons = {
            waiting_payment: 'bi-cash-coin',
            paid: 'bi-check-circle-fill',
            preparing: 'bi-cup-hot',
            ready: 'bi-bag-check',
        };

        const toastCopy = {
            paid: {
                title: 'Pembayaran diterima',
                message: 'Pesanan kamu akan segera masuk ke dapur.',
                icon: 'bi-check-circle-fill',
            },
            preparing: {
                title: 'Pesanan diproses',
                message: 'Dapur sedang menyiapkan pesanan kamu.',
                icon: 'bi-cup-hot',
            },
            ready: {
                title: 'Pesanan siap',
                message: 'Silakan ambil pesanan atau tunggu staf mengantar ke meja.',
                icon: 'bi-bag-check',
            },
        };

        function unlockSound() {
            if (audioContext) {
                if (audioContext.state === 'suspended') {
                    audioContext.resume().catch(function() {});
                }

                return;
            }

            const AudioContext = window.AudioContext || window.webkitAudioContext;
            if (!AudioContext) return;

            audioContext = new AudioContext();
            if (audioContext.state === 'suspended') {
                audioContext.resume().catch(function() {});
            }
        }

        ['pointerdown', 'touchstart', 'keydown', 'click'].forEach(function(eventName) {
            document.addEventListener(eventName, unlockSound, {
                once: true,
                passive: true,
            });
        });

        function playStatusSound() {
            unlockSound();

            if (!audioContext || audioContext.state !== 'running') {
                return;
            }

            const startAt = audioContext.currentTime;
            const gain = audioContext.createGain();
            gain.gain.setValueAtTime(0.0001, startAt);
            gain.gain.exponentialRampToValueAtTime(0.08, startAt + 0.02);
            gain.gain.exponentialRampToValueAtTime(0.0001, startAt + 0.34);
            gain.connect(audioContext.destination);

            [660, 880].forEach(function(frequency, index) {
                const oscillator = audioContext.createOscillator();
                oscillator.type = 'sine';
                oscillator.frequency.setValueAtTime(frequency, startAt + (index * 0.11));
                oscillator.connect(gain);
                oscillator.start(startAt + (index * 0.11));
                oscillator.stop(startAt + 0.18 + (index * 0.11));
            });
        }

        function showCustomerToast(stage, fallbackLabel, fallbackMessage) {
            if (!toast) return;

            const copy = toastCopy[stage] || {
                title: fallbackLabel || 'Status diperbarui',
                message: fallbackMessage || 'Pesanan kamu punya update baru.',
                icon: statusIcons[stage] || 'bi-bell',
            };

            toastTitle.textContent = copy.title;
            toastMessage.textContent = copy.message;
            toastIcon.innerHTML = `<i class="bi ${copy.icon}"></i>`;
            toast.classList.add('show');

            clearTimeout(toastTimer);
            toastTimer = setTimeout(function() {
                toast.classList.remove('show');
            }, 4200);

            playStatusSound();
            if (navigator.vibrate) {
                navigator.vibrate(stage === 'ready' ? [120, 70, 120] : [120]);
            }
        }

        function updateStatusUI(data) {
            if (!data) return;

            statusLabel.textContent = data.label || 'Status Pesanan';
            statusMessage.textContent = data.message || '';
            statusIcon.innerHTML = `<i class="bi ${statusIcons[data.stage] || 'bi-receipt-cutoff'}"></i>`;

            ['created', 'paid', 'preparing', 'ready'].forEach(function(step) {
                const element = document.querySelector(`[data-step="${step}"]`);
                if (!element) return;
                element.classList.remove('done', 'current');

                const state = data.steps && data.steps[step] ? data.steps[step] : 'pending';
                if (state === 'done' || state === 'current') {
                    element.classList.add(state);
                }
            });
        }

        if (copyOrderCode && orderCodeText) {
            function showCopySuccess() {
                copyOrderCode.classList.add('copied');
                copyOrderCode.innerHTML = '<i class="bi bi-check-lg"></i>';

                setTimeout(function() {
                    copyOrderCode.classList.remove('copied');
                    copyOrderCode.innerHTML = '<i class="bi bi-copy"></i>';
                }, 1400);
            }

            function fallbackCopy(text) {
                const temp = document.createElement('textarea');
                temp.value = text;
                temp.setAttribute('readonly', '');
                temp.style.position = 'fixed';
                temp.style.left = '-9999px';
                temp.style.top = '0';
                document.body.appendChild(temp);
                temp.focus();
                temp.select();

                let copied = false;
                try {
                    copied = document.execCommand('copy');
                } catch (error) {
                    copied = false;
                }

                temp.remove();
                return copied;
            }

            copyOrderCode.addEventListener('click', async function() {
                const code = orderCodeText.textContent.trim();

                try {
                    if (navigator.clipboard && window.isSecureContext) {
                        await navigator.clipboard.writeText(code);
                        showCopySuccess();
                        return;
                    }

                    if (fallbackCopy(code)) {
                        showCopySuccess();
                        return;
                    }

                    window.prompt('Copy kode order:', code);
                } catch (error) {
                    if (fallbackCopy(code)) {
                        showCopySuccess();
                        return;
                    }

                    window.prompt('Copy kode order:', code);
                }
            });
        }

        async function pollOrderStatus() {
            try {
                const res = await fetch("{{ route('customer-menu.order-status', ['token' => $token, 'order' => $order->order_code]) }}", {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                if (!res.ok) return;

                const data = await res.json();
                updateStatusUI(data);

                if (data.stage && data.stage !== lastStage) {
                    showCustomerToast(data.stage, data.label, data.message);
                    lastStage = data.stage;
                }
            } catch (e) {
                console.error("gagal ambil status pesanan", e);
            }
        }

        function tick() {
            if (!finishTime) return;
            const distance = finishTime - Date.now();

            if (distance <= 0) {
                countdownEl.innerHTML = "Pesanan Hampir Siap";
                clearInterval(tickInterval);
                tickInterval = null;
                return;
            }

            const minutes = Math.floor(distance / 60000);
            const seconds = Math.floor((distance % 60000) / 1000);
            countdownEl.innerHTML = String(minutes).padStart(2,'0') + ":" + String(seconds).padStart(2,'0');
        }

        async function pollStatus() {
            try {
                const res = await fetch("{{ route('customer.order.countdown', ['token' => $token, 'order' => $order->id]) }}");
                const data = await res.json();

                if (data.has_queue && data.countdown_end) {
                    card.style.display = "block";
                    finishTime = new Date(data.countdown_end).getTime();

                    if (!tickInterval) {
                        tick();
                        tickInterval = setInterval(tick, 1000);
                    }
                } else {
                    card.style.display = "none";
                }
            } catch (e) {
                console.error("gagal ambil status countdown", e);
            }
        }

        pollStatus();
        pollOrderStatus();
        setInterval(pollStatus, 5000); // cek ke server tiap 5 detik
        setInterval(pollOrderStatus, 3000);
    });
    </script>
</body>
</html>
