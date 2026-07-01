<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Receipt {{ $order->order_code }}</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #fff;
            color: #111;
        }

        .receipt {
            width: 280px;
            margin: 0 auto;
            padding: 14px;
            font-size: 12px;
        }

        .center { text-align: center; }
        .bold { font-weight: 700; }
        .line { border-top: 1px dashed #999; margin: 10px 0; }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin: 4px 0;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: 700;
        }

        .muted {
            color: #555;
            font-size: 11px;
        }

        @media print {
            @page {
                margin: 0;
                size: 80mm auto;
            }

            body {
                margin: 0;
            }

            .receipt {
                width: 72mm;
                padding: 4mm;
            }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="receipt">
        <div class="center">
            <div class="bold">MEJA TERAKHIR COFFEE</div>
            <div>Cashier Receipt</div>
        </div>

        <div class="line"></div>

        <div class="row">
            <span>Order</span>
            <span>{{ $order->order_code }}</span>
        </div>
        <div class="row">
            <span>Table</span>
            <span>{{ $order->table->table_number ?? '-' }}</span>
        </div>
        <div class="row">
            <span>Date</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="row">
            <span>Cashier</span>
            <span>{{ $order->payment->processedBy->name ?? 'Online Payment' }}</span>
        </div>

        <div class="line"></div>

        @foreach ($order->orderItems as $item)
            <div class="item">
                <div class="row">
                    <span class="item-name">{{ $item->quantity }}x {{ $item->menuItem->name ?? 'Menu' }}</span>
                    <span>Rp{{ number_format($item->subtotal, 0, ',', '.') }}</span>
                </div>

                @if ($item->notes)
                    <div class="muted">{{ $item->notes }}</div>
                @endif
            </div>
        @endforeach

        <div class="line"></div>

        <div class="row">
            <span>Subtotal</span>
            <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Discount</span>
            <span>-Rp{{ number_format($order->discount_total, 0, ',', '.') }}</span>
        </div>
        <div class="row bold">
            <span>Total</span>
            <span>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
        </div>

        <div class="line"></div>

        <div class="row">
            <span>Payment</span>
            <span>{{ strtoupper($order->payment_method ?? '-') }}</span>
        </div>
        <div class="row">
            <span>Cash Received</span>
            <span>Rp{{ number_format(optional($order->payment)->amount_paid ?? $order->grand_total, 0, ',', '.') }}</span>
        </div>
        <div class="row">
            <span>Change</span>
            <span>Rp{{ number_format(optional($order->payment)->change_amount ?? 0, 0, ',', '.') }}</span>
        </div>

        <div class="line"></div>

        <div class="center">
            <div>Thank you</div>
            <div class="muted">Please keep this receipt</div>
        </div>
    </div>
</body>
</html>