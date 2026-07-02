@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

@if ($todayReport['payment_breakdown']->isNotEmpty())
    <div class="adm-payments">
        @foreach ($todayReport['payment_breakdown'] as $payment)
            <div class="adm-payment-row">
                <div>
                    <div class="adm-payment-method">{{ $payment->payment_method }}</div>
                    <div class="adm-payment-note">{{ $payment->total_orders }} orders</div>
                </div>
                <div class="adm-payment-value">{{ $money($payment->total_revenue) }}</div>
            </div>
        @endforeach
    </div>
@else
    <div class="adm-empty">Belum ada pembayaran hari ini.</div>
@endif
