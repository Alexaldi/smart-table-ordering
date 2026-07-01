 @forelse ($orders as $order)
     @php
         $isCash = $order->payment_method === 'cash';
         $isPaid = $order->payment_status === 'paid';
         $isCashWaiting = $isCash && !$isPaid;
         $paymentMethod = $order->payment_method ?? 'cashless';
         $paymentStatus = $order->payment_status;
     @endphp

     <div class="ks-order" data-payment-method="{{ $paymentMethod }}" data-payment-status="{{ $paymentStatus }}"
         data-order-status="{{ $order->status }}">
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
             $itemsForModal = $order->orderItems
                 ->map(function ($item) {
                     return [
                         'name' => $item->menuItem->name ?? 'Menu',
                         'quantity' => $item->quantity,
                         'notes' => $item->notes,
                         'subtotal' => (int) $item->subtotal,
                     ];
                 })
                 ->values();
         @endphp
         <div class="ks-actions-row">
             <button type="button" class="ks-small-btn btn-order-detail" data-order-code="{{ $order->order_code }}"
                 data-table="{{ $order->table->table_number ?? '-' }}"
                 data-payment-method="{{ strtoupper($order->payment_method ?? 'CASHLESS') }}"
                 data-payment-status="{{ strtoupper($order->payment_status) }}"
                 data-cashier="{{ $order->payment->processedBy->name ?? 'Online Payment' }}"
                 data-subtotal="{{ (int) $order->subtotal }}" data-discount="{{ (int) $order->discount_total }}"
                 data-grand-total="{{ (int) $order->grand_total }}"
                 data-amount-paid="{{ (int) optional($order->payment)->amount_paid }}"
                 data-change-amount="{{ (int) optional($order->payment)->change_amount }}"
                 data-items='@json($itemsForModal)'>
                 Detail
             </button>

             @php
                 $rejectItemsForModal = $order->orderItems
                     ->map(function ($item) {
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
                     })
                     ->values();
             @endphp
             @if ($order->payment_status === 'paid')
                 <button type="button" class="ks-small-btn btn-reject-items"
                     style="background:#dc2626;border-color:#dc2626;color:#ffffff;"
                     data-reject-url="{{ route('kasir.orders.reject-items', $order->id) }}"
                     data-order-code="{{ $order->order_code }}" data-items='@json($rejectItemsForModal)'>
                     Reject
                 </button>
             @endif

             @if ($isCashWaiting)
                 <button type="button" class="ks-small-btn primary btn-pay-cash"
                     data-pay-url="{{ route('kasir.orders.pay-cash', $order->id) }}"
                     data-total="{{ (int) $order->grand_total }}" data-order-code="{{ $order->order_code }}">
                     Pay
                 </button>
             @elseif ($isPaid)
                 <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank"
                     class="ks-small-btn primary">
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
