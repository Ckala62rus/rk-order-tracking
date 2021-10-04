<?php

namespace App\Services;

use Carbon\Carbon;

class TimerExecuteService
{

    /**
     * @var Carbon
     */
    public static Carbon $start;

    /**
     * @var int
     */
    public static int $stop;

    /**
     * Set timer
     */
    public static function Start(): void
    {
        self::$start = Carbon::now();
    }

    /**
     * Stop timer and return execution seconds
     * @return int
     */
    public static function Stop(): int
    {
        return self::$stop = self::$start->diffInSeconds(Carbon::now());
    }
}
