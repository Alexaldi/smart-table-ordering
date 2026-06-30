<?php

namespace App\Services;

use App\Models\Order;
use Midtrans\Config;
use Midtrans\Snap;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production');
        Config::$isSanitized = (bool) config('midtrans.is_sanitized');
        Config::$is3ds = (bool) config('midtrans.is_3ds');
    }

    public function getSnapToken(Order $order): string
    {
        $order->loadMissing('orderItems.menuItem', 'table');

        return Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $order->order_code,
                'gross_amount' => (int) round($order->grand_total),
            ],
            'customer_details' => [
                'first_name' => $order->customer_name,
                'email' => $order->customer_email,
                'phone' => $order->customer_phone,
            ],
            'item_details' => $order->orderItems->map(function ($item) {
                return [
                    'id' => (string) $item->menu_item_id,
                    'price' => (int) round($item->subtotal / $item->quantity),
                    'quantity' => (int) $item->quantity,
                    'name' => $item->menuItem->name ?? 'Menu',
                ];
            })->values()->toArray(),
        ]);
    }
}