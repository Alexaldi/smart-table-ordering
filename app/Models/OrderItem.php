<?php

namespace App\Models;

use App\Models\Discount;
use App\Models\KitchenQueue;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\RejectItem;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['order_id', 'menu_item_id', 'discount_id', 'quantity', 'unit_price', 'discount_amount', 'subtotal', 'notes', 'status'])]
class OrderItem extends Model
{
    use HasFactory;

    protected $casts = [
        'unit_price' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function discount(): BelongsTo
    {
        return $this->belongsTo(Discount::class);
    }

    public function rejectItems(): HasMany
    {
        return $this->hasMany(RejectItem::class);
    }

    public function kitchenQueues(): HasMany
    {
        return $this->hasMany(KitchenQueue::class);
    }
}
