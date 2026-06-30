<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use SerializesModels;

    public Order $order;

    public function __construct(Order $order)
    {
        $this->order = $order->loadMissing('orderItems.menuItem', 'table');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Receipt Pesanan ' . $this->order->order_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'Customer.emails.order-receipt',
            with: [
                'order' => $this->order,
            ],
        );
    }
}