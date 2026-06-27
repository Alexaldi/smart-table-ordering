<?php

namespace App\Models;

use App\Models\Category;
use App\Models\Discount;
use App\Models\OrderItem;
use App\Models\StockLog;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['category_id', 'name', 'description', 'price', 'image_url', 'estimated_minutes', 'stock', 'is_available', 'is_active'])]
class MenuItem extends Model
{
    use HasFactory;

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class);
    }

    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class, 'menu_discounts');
    }

    public function menuDiscounts(): HasMany
    {
        return $this->hasMany(MenuDiscount::class, 'menu_item_id');
    }
}
