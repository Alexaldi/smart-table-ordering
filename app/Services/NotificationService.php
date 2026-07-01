<?php

namespace App\Services;

use App\Models\Notification;
use InvalidArgumentException;

class NotificationService
{
    private const ROLES = ['kasir', 'dapur', 'admin', 'owner'];

    public function notifyRole(string $role, string $type, int $referenceId, string $message): Notification
    {
        if (! in_array($role, self::ROLES, true)) {
            throw new InvalidArgumentException("Role {$role} tidak valid untuk notifikasi.");
        }

        return Notification::create([
            'target_role' => $role,
            'type' => $type,
            'reference_id' => $referenceId,
            'message' => $message,
            'is_read' => false,
        ]);
    }

    public function notifyRoles(array $roles, string $type, int $referenceId, string $message): void
    {
        foreach (array_unique($roles) as $role) {
            $this->notifyRole($role, $type, $referenceId, $message);
        }
    }
}
