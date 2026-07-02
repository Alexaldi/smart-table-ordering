@php
    $money = fn ($value) => 'Rp ' . number_format((float) $value, 0, ',', '.');
@endphp

<article class="adm-card adm-op green">
    <div class="adm-op-label">Revenue Hari Ini</div>
    <div class="adm-op-value">{{ $money($todayReport['gross_revenue']) }}</div>
    <div class="adm-op-note">Net estimate {{ $money($todayReport['net_revenue']) }}</div>
</article>

<article class="adm-card adm-op">
    <div class="adm-op-label">Paid Orders</div>
    <div class="adm-op-value">{{ number_format($todayReport['total_orders'], 0, ',', '.') }}</div>
    <div class="adm-op-note">AOV {{ $money($todayReport['average_order_value']) }}</div>
</article>

<article class="adm-card adm-op amber">
    <div class="adm-op-label">Pending Payment</div>
    <div class="adm-op-value">{{ number_format($pendingPaymentOrders, 0, ',', '.') }}</div>
    <div class="adm-op-note">Order belum dibayar hari ini</div>
</article>

<article class="adm-card adm-op red">
    <div class="adm-op-label">Antrian Dapur</div>
    <div class="adm-op-value">{{ number_format($activeKitchenQueues, 0, ',', '.') }}</div>
    <div class="adm-op-note">{{ number_format($completedKitchenQueues, 0, ',', '.') }} item selesai hari ini</div>
</article>
