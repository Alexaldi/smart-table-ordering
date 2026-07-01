<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Summary</title>

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
        }
    </style>
</head>

<body>
    <main class="page">
        <header class="topbar">
            <h1>Order Summary</h1>
        </header>

        <div class="content">
            <div class="order-type">
                <span>Order Type</span>
                <strong>
                    Dine In
                    <span class="check"><i class="bi bi-check-lg"></i></span>
                </strong>
            </div>

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
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const card = document.getElementById("countdown-card");
        const countdownEl = document.getElementById("countdown");
        let finishTime = null;
        let tickInterval = null;

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
        setInterval(pollStatus, 5000); // cek ke server tiap 5 detik
    });
    </script>
</body>
</html>