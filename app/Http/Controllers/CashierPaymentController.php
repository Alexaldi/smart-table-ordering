<?php

namespace App\Http\Controllers;

use App\Models\KitchenQueue;
use App\Models\Order;
use App\Models\Payment;
use App\Models\RejectItem;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CashierPaymentController extends Controller
{
    private function dashboardData(): array
    {
        $shift = auth()->user()->shift;

        $now = now();

        $shiftStart = $shift->startDateTimeFrom($now);
        $shiftEnd = $shift->endDateTimeFrom($now);

        $orders = Order::with([
            'table',
            'orderItems.menuItem',
            'orderItems.rejectItems',
            'payment.processedBy',
        ])
            ->whereBetween('created_at', [$shiftStart, $shiftEnd])
            ->latest()
            ->get();

        $stats = [
            'new_orders' => $orders->where('status', 'pending_payment')->count(),
            'processing_orders' => $orders->where('status', 'processing')->count(),
            'payments' => $orders->where('payment_status', 'paid')->count(),
            'active_tables' => $orders->pluck('table_id')->filter()->unique()->count(),
        ];

        return compact('orders', 'stats');
    }

    public function realtime()
    {
        $data = $this->dashboardData();

        return response()->json([
            'stats_html' => view('kasir.partials.stats', [
                'stats' => $data['stats'],
            ])->render(),

            'orders_html' => view('kasir.partials.orders', [
                'orders' => $data['orders'],
            ])->render(),
        ]);
    }

    public function index()
    {
        return view('kasir.dashboard', $this->dashboardData());
    }

    public function payCash(Request $request, Order $order)
    {
        $validated = $request->validate([
            'amount_paid' => ['required', 'numeric', 'min:'.$order->grand_total],
        ]);

        $changeAmount = $validated['amount_paid'] - $order->grand_total;
        $wasPaidBefore = $order->payment_status === 'paid';

        DB::transaction(function () use ($order, $validated, $changeAmount) {
            $order->update([
                'status' => 'paid',
                'payment_status' => 'paid',
                'payment_method' => 'cash',
            ]);

            Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'processed_by' => auth()->id(),
                    'payment_method' => 'cash',
                    'amount_paid' => $validated['amount_paid'],
                    'change_amount' => $changeAmount,
                    'paid_at' => now(),
                ]
            );
        });

        if (! $wasPaidBefore) {
            app(NotificationService::class)->notifyRoles(
                ['dapur', 'admin'],
                'order_paid_cash',
                $order->id,
                "Order {$order->order_code} sudah dibayar cash dan siap dimasak."
            );
        }

        return response()->json([
            'message' => 'Cash payment completed successfully.',
            'change_amount' => $changeAmount,
        ]);
    }

    public function receipt(Order $order)
    {
        $order->load(['table', 'orderItems.menuItem', 'payment.processedBy']);

        abort_if($order->payment_status !== 'paid', 403);

        return view('kasir.receipt', compact('order'));
    }

    public function rejectItems(Request $request, Order $order)
    {
        $validated = $request->validate([
            'items' => ['required', 'array', 'min:1'],
            'items.*.order_item_id' => ['required', 'integer', 'exists:order_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'reason' => ['required', 'string', 'max:500'],
        ]);

        DB::transaction(function () use ($order, $validated) {
            foreach ($validated['items'] as $selectedItem) {
                $orderItem = $order->orderItems()
                    ->where('id', $selectedItem['order_item_id'])
                    ->lockForUpdate()
                    ->firstOrFail();

                $rejectQty = (int) $selectedItem['quantity'];

                if ($rejectQty > $orderItem->quantity) {
                    throw ValidationException::withMessages([
                        'items' => 'Reject quantity cannot be greater than ordered quantity.',
                    ]);
                }

                $unitPrice = $orderItem->quantity > 0
                    ? $orderItem->subtotal / $orderItem->quantity
                    : 0;

                $reject = RejectItem::create([
                    'order_item_id' => $orderItem->id,
                    'quantity' => $rejectQty,
                    'reported_by' => auth()->id(),
                    'reason' => $validated['reason'],
                    'action' => 'remake',
                    'cost_impact' => $unitPrice * $rejectQty,
                ]);

                // kirim ulang ke antrian dapur
                KitchenQueue::create([
                    'order_item_id' => $orderItem->id,
                    'reject_item_id' => $reject->id,
                    'quantity' => $rejectQty,
                    'queue_type' => 'remake',
                    'status' => 'queued',
                    'queued_at' => now(),
                ]);
            }
        });

        $message = "Remake item untuk order {$order->order_code} masuk dari kasir.";
        app(NotificationService::class)->notifyRoles(
            ['dapur', 'admin'],
            'order_item_rejected',
            $order->id,
            $message
        );

        return response()->json([
            'message' => 'Selected items have been rejected and sent back to the kitchen.',
        ]);
    }
}
