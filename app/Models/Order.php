<?php

namespace App\Models;

use App\Models\DiningTable;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable(['order_code', 'table_id', 'session_token', 'customer_name', 'customer_phone', 'customer_email','status', 'payment_method', 'payment_status', 'subtotal', 'discount_total', 'grand_total', 'notes'])]
class Order extends Model
{
    use HasFactory;

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount_total' => 'decimal:2',
        'grand_total' => 'decimal:2',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(DiningTable::class, 'table_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }
}
