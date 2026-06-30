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

    public function activeMenuDiscount(): ?MenuDiscount
    {
        if ($this->relationLoaded('menuDiscounts')) {
            return $this->menuDiscounts
                ->filter(function (MenuDiscount $menuDiscount) {
                    $discount = $menuDiscount->discount;

                    return $discount
                        && $discount->start_date <= now()
                        && $discount->end_date >= now();
                })
                ->sortByDesc(fn (MenuDiscount $menuDiscount) => $menuDiscount->discount->percentage)
                ->first();
        }

        return $this->menuDiscounts()
            ->with('discount')
            ->whereHas('discount', fn ($query) => $query
                ->where('start_date', '<=', now())
                ->where('end_date', '>=', now()))
            ->join('discounts', 'menu_discounts.discount_id', '=', 'discounts.id')
            ->orderByDesc('discounts.percentage')
            ->select('menu_discounts.*')
            ->first();
    }

    public function activeDiscount(): ?Discount
    {
        return $this->activeMenuDiscount()?->discount;
    }

    public function discountPercentage(): float
    {
        return (float) ($this->activeDiscount()?->percentage ?? 0);
    }

    public function discountAmount(): float
    {
        return round(((float) $this->price * $this->discountPercentage()) / 100, 2);
    }

    public function finalPrice(): float
    {
        return max(0, round((float) $this->price - $this->discountAmount(), 2));
    }

    public function hasActiveDiscount(): bool
    {
        return $this->activeDiscount() !== null;
    }
}
