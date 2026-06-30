@php
    $heroImage = 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?auto=format&fit=crop&w=1600&q=85';

    $summaryUrl = route('customer-menu.order-summary', [
        'token' => $order->table->qr_token,
        'order' => $order->order_code,
    ]);
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Receipt Pesanan</title>
</head>
<body style="margin:0;background:#f6f7fb;font-family:Arial,sans-serif;color:#111827;">
    <div style="max-width:520px;margin:0 auto;background:#ffffff;padding:24px;">
        <img src="{{ $heroImage }}" alt="Receipt Image" style="width:100%;height:180px;object-fit:cover;border-radius:10px;display:block;">

        <div style="text-align:center;margin-top:14px;">
            <h2 style="margin:0;font-size:18px;">Pesanan Berhasil Dibayar</h2>
            <p style="margin:6px 0 0;color:#6b7280;">Order #{{ $order->order_code }}</p>
        </div>

        <div style="text-align:center;margin:18px 0;">
            <div style="font-size:13px;color:#6b7280;">Total Transaksi</div>
            <div style="font-size:24px;font-weight:700;">
                Rp{{ number_format($order->grand_total, 0, ',', '.') }}
            </div>
        </div>

        <a href="{{ $summaryUrl }}" style="display:block;background:#ff6a00;color:#ffffff;text-align:center;text-decoration:none;padding:12px;border-radius:8px;font-weight:700;">
            Lihat Pesananmu
        </a>

        <div style="background:#f9fafb;border-radius:10px;padding:14px;margin-top:18px;">
            <h3 style="font-size:14px;margin:0 0 10px;">Informasi Pemesanan</h3>
            <p style="margin:4px 0;">Nama: {{ $order->customer_name }}</p>
            <p style="margin:4px 0;">No HP: {{ $order->customer_phone }}</p>
            <p style="margin:4px 0;">Email: {{ $order->customer_email }}</p>
            <p style="margin:4px 0;">Meja: {{ $order->table->table_number ?? '-' }}</p>
        </div>

        <div style="margin-top:18px;">
            <h3 style="font-size:14px;margin-bottom:10px;">Detail Pesanan</h3>

            @foreach ($order->orderItems as $item)
                <div style="display:flex;justify-content:space-between;border-bottom:1px solid #e5e7eb;padding:10px 0;">
                    <div>
                        <div style="font-weight:700;">
                            {{ $item->quantity }}x {{ $item->menuItem->name ?? 'Menu' }}
                        </div>

                        @if ($item->notes)
                            <div style="font-size:12px;color:#6b7280;">{{ $item->notes }}</div>
                        @endif
                    </div>

                    <div style="font-weight:600;">
                        Rp{{ number_format($item->subtotal, 0, ',', '.') }}
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top:16px;">
            <div style="display:flex;justify-content:space-between;margin:6px 0;">
                <span>Subtotal</span>
                <span>Rp{{ number_format($order->subtotal, 0, ',', '.') }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;margin:6px 0;">
                <span>Diskon</span>
                <span>-Rp{{ number_format($order->discount_total, 0, ',', '.') }}</span>
            </div>

            <div style="display:flex;justify-content:space-between;margin-top:12px;font-size:18px;font-weight:700;">
                <span>Grand Total</span>
                <span>Rp{{ number_format($order->grand_total, 0, ',', '.') }}</span>
            </div>
        </div>

        <p style="text-align:center;color:#6b7280;font-size:12px;margin-top:24px;">
            Terima kasih atas pembayaranmu.
        </p>
    </div>
</body>
</html>