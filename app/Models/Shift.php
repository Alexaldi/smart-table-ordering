<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

#[Fillable(['name', 'start_time', 'end_time'])]
class Shift extends Model
{
    use HasFactory;

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function isActiveAt(?Carbon $time = null): bool
    {
        $time ??= now();

        $currentSeconds = $this->secondsFromTime($time->format('H:i:s'));
        $startSeconds = $this->secondsFromTime($this->start_time);
        $endSeconds = $this->secondsFromTime($this->end_time);

        if ($startSeconds === $endSeconds) {
            return false;
        }

        if ($endSeconds > $startSeconds) {
            return $currentSeconds >= $startSeconds && $currentSeconds < $endSeconds;
        }

        return $currentSeconds >= $startSeconds || $currentSeconds < $endSeconds;
    }

    public function endDateTimeFrom(?Carbon $time = null): Carbon
    {
        $time ??= now();

        $end = $time->copy()->setTimeFromTimeString($this->end_time);
        $startSeconds = $this->secondsFromTime($this->start_time);
        $endSeconds = $this->secondsFromTime($this->end_time);
        $currentSeconds = $this->secondsFromTime($time->format('H:i:s'));

        if ($endSeconds <= $startSeconds && $currentSeconds >= $startSeconds) {
            return $end->addDay();
        }

        return $end;
    }

    private function secondsFromTime(string $time): int
    {
        [$hours, $minutes, $seconds] = array_pad(explode(':', $time), 3, 0);

        return ((int) $hours * 3600) + ((int) $minutes * 60) + (int) $seconds;
    }
}
