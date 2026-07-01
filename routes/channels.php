<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('role.{role}', function ($user, string $role) {
    return $user->role === $role;
});
