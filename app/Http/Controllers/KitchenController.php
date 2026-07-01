<?php

namespace App\Http\Controllers;

use App\Models\KitchenQueue;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KitchenController extends Controller
{
    
    public function index()
    {
        $user = auth()->user();
        $user->loadMissing('shift');
        $shift = $user->shift;

        abort_unless($shift, 403, 'Belum ada shift aktif.');

        $shiftStart = Carbon::today()->setTimeFromTimeString($shift->start_time);
        $shiftEnd = Carbon::today()->setTimeFromTimeString($shift->end_time);
        if ($shiftEnd->lessThanOrEqualTo($shiftStart)) {
            $shiftEnd->addDay();
        }

        $queues = KitchenQueue::with([
                'orderItem.order.table',
                'orderItem.menuItem',
                'rejectItem',
                'confirmedBy',
            ])
            ->whereBetween('queued_at', [$shiftStart, $shiftEnd])
            ->whereHas('orderItem.order', fn ($q) => $q->where('payment_status', 'paid'))
            ->whereIn('status', ['queued', 'preparing'])
            ->get();

        $orders = $queues->groupBy(fn ($q) => $q->orderItem->order_id . '-' . $q->queue_type)
            ->map(function ($items) {
                $order = $items->first()->orderItem->order;
                return (object) [
                    'order'      => $order,
                    'queue_type' => $items->first()->queue_type,
                    'status'     => $items->contains(fn ($q) => $q->status === 'preparing') ? 'preparing' : 'queued',
                    'queued_at'  => $items->min('queued_at'),
                    'total_qty'  => $items->sum('quantity'),
                    'reasons'    => $items->pluck('rejectItem.reason')->filter()->unique()->values(),
                ];
            })
            ->sortBy('queued_at')
            ->values();

        $stats = [
            'queued'      => $queues->where('status', 'queued')->count(),
            'preparing'   => $queues->where('status', 'preparing')->count(),
            'total_items' => $queues->sum('quantity'),
        ];

        return view('dapur.dashboard', compact('orders', 'stats'));
    }

    public function show(Order $order)
    {
        abort_if($order->payment_status !== 'paid', 403);

        $itemIds = $order->orderItems()->pluck('id');

        $queues = KitchenQueue::with(['orderItem.menuItem', 'rejectItem'])
            ->whereIn('order_item_id', $itemIds)
            ->whereIn('status', ['queued', 'preparing', 'done'])
            ->get()
            ->sortBy('id')
            ->values();

        abort_if($queues->isEmpty(), 404);

        $groups = $queues->groupBy('queue_type')->map(function ($items, $type) {
            if ($items->contains(fn ($q) => $q->status === 'preparing')) {
                $status = 'preparing';
            } elseif ($items->every(fn ($q) => $q->status === 'done')) {
                $status = 'done';
            } else {
                $status = 'queued';
            }

            return (object) [
                'queue_type' => $type,
                'status'     => $status,
                'items'      => $items,
            ];
        })->values();

        $order->loadMissing('table');

        return view('dapur.order-detail', compact('order', 'groups'));
    }

    public function prepare(Request $request, Order $order)
    {
        abort_if($order->payment_status !== 'paid', 403);

        $type = $request->query('type', 'new_order');
        abort_unless(in_array($type, ['new_order', 'remake']), 422, 'Tipe queue tidak valid.');

        DB::transaction(function () use ($order, $type) {
            $itemIds = $order->orderItems()->pluck('id');

            $updated = KitchenQueue::whereIn('order_item_id', $itemIds)
                ->where('queue_type', $type)
                ->where('status', 'queued')
                ->update([
                    'status'       => 'preparing',
                    'confirmed_by' => auth()->id(),
                ]);

            abort_if($updated === 0, 404, 'Tidak ada item queued untuk order ini.');

            if ($type === 'new_order') {
                OrderItem::whereIn('id', $itemIds)
                    ->where('status', 'pending')
                    ->update(['status' => 'preparing']);
            }

            if ($order->status === 'paid') {
                $order->update(['status' => 'processing']);
            }
        });

        return response()->json([
            'message'  => 'Order sedang diproses.',
            'redirect' => route('dapur.orders.show', $order->id),
        ]);
    }

    public function done(Request $request, Order $order)
    {
        abort_if($order->payment_status !== 'paid', 403);

        $type = $request->query('type', 'new_order');
        abort_unless(in_array($type, ['new_order', 'remake']), 422, 'Tipe queue tidak valid.');

        DB::transaction(function () use ($order, $type) {
            $itemIds = $order->orderItems()->pluck('id');

            $updated = KitchenQueue::whereIn('order_item_id', $itemIds)
                ->where('queue_type', $type)
                ->where('status', 'preparing')
                ->update([
                    'status'       => 'done',
                    'confirmed_by' => auth()->id(),
                    'done_at'      => now(),
                ]);

            abort_if($updated === 0, 404, 'Tidak ada item preparing untuk order ini.');

            if ($type === 'new_order') {
                OrderItem::whereIn('id', $itemIds)
                    ->where('status', 'preparing')
                    ->update(['status' => 'ready']);
            }

            $remaining = KitchenQueue::whereIn('order_item_id', $itemIds)
                ->whereIn('status', ['queued', 'preparing'])
                ->exists();

            if (! $remaining) {
                $order->update(['status' => 'ready']);
            }
        });

        return response()->json([
            'message'  => 'Order selesai dimasak.',
            'redirect' => route('dapur.dashboard'),
        ]);
    }
}