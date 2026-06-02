<?php

namespace App\Models;

use App\Models\MenuItem;
use App\Models\MenuDiscount;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'percentage', 'start_date', 'end_date', 'created_by'])]
class Discount extends Model
{
    use HasFactory;

    protected $casts = [
        'percentage' => 'decimal:2',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(MenuItem::class, 'menu_discounts');
    }

    public function menuDiscounts(): HasMany
    {
        return $this->hasMany(MenuDiscount::class);
    }
}
