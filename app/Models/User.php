<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class, 'created_by');
    }

    public function stockLogs(): HasMany
    {
        return $this->hasMany(StockLog::class, 'changed_by');
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class, 'processed_by');
    }

    public function rejectItems(): HasMany
    {
        return $this->hasMany(RejectItem::class, 'reported_by');
    }

    public function kitchenQueues(): HasMany
    {
        return $this->hasMany(KitchenQueue::class, 'confirmed_by');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
