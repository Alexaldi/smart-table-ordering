<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\StockLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Midtrans\Config;
use Midtrans\Notification;
use App\Mail\OrderReceiptMail;
use Illuminate\Support\Facades\Mail;

class MidtransNotificationController extends Controller
{

    public function handle(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');

        $notif = new Notification();

        $orderCode = $notif->order_id;
        $transactionStatus = $notif->transaction_status;
        $fraudStatus = $notif->fraud_status ?? null;
        $paymentType = $notif->payment_type;

        $order = Order::where('order_code', $orderCode)->first();

        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'accept' || $fraudStatus === null) {
                $order->update([
                    'status' => 'paid',
                    'payment_status' => 'paid',
                    'payment_method' => $this->mapPaymentMethod($paymentType),
                ]);

                Payment::updateOrCreate(
                    ['order_id' => $order->id],
                    [
                        'payment_method' => $this->mapPaymentMethod($paymentType),
                        'amount_paid' => $order->grand_total,
                        'change_amount' => 0,
                        'midtrans_order_id' => $orderCode,
                        'paid_at' => now(),
                    ]
                );

                if (!empty($order->customer_email)) {
                    $order->load('orderItems.menuItem', 'table');

                    Mail::to($order->customer_email)->send(new OrderReceiptMail($order));
                }
            }
        } elseif ($transactionStatus == 'pending') {
            $order->update(['payment_status' => 'unpaid']);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $this->restoreStockAndCancel($order);
        }

        return response()->json(['message' => 'OK']);
    }

    private function mapPaymentMethod(string $paymentType): string
    {
        return match (true) {
            str_contains($paymentType, 'qris') => 'qris',
            str_contains($paymentType, 'bank_transfer') => 'transfer',
            default => 'other',
        };
    }

    private function restoreStockAndCancel(Order $order): void
    {
        DB::transaction(function () use ($order) {
            foreach ($order->orderItems as $item) {
                $menuItem = $item->menuItem;
                $stockBefore = $menuItem->stock;

                $menuItem->increment('stock', $item->quantity);
                $menuItem->refresh();

                StockLog::create([
                    'menu_item_id' => $menuItem->id,
                    'changed_by' => null,
                    'change_type' => 'reject',
                    'quantity' => $item->quantity,
                    'stock_before' => $stockBefore,
                    'stock_after' => $menuItem->stock,
                    'reference_id' => $order->id,
                ]);

                $menuItem->update(['is_available' => $menuItem->stock > 0]);
            }

            $order->update([
                'status' => 'cancelled',
                'payment_status' => 'unpaid',
            ]);
        });
    }
}
