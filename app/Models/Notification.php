<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['target_role', 'type', 'reference_id', 'message', 'is_read'])]
class Notification extends Model
{
    use HasFactory;

    protected $casts = [
        'is_read' => 'boolean',
    ];
}
