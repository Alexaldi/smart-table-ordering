<?php

namespace App\Models;

use App\Models\MenuItem;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'description'])]
class Category extends Model
{
    use HasFactory;

    public function menuItems(): HasMany
    {
        return $this->hasMany(MenuItem::class);
    }
}
