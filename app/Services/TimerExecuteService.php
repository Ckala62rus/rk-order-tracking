<?php

namespace App\Services;

use Carbon\Carbon;

class TimerExecuteService
{
    /**
     * @var float
     */
    public static float $start;

    /**
     * @var float
     */
    public static float $stop;

    /**
     * Set timer
     */
    public static function Start(): void
    {
        self::$start = microtime(true);
    }

    /**
     * Stop timer and return execution seconds
     * @return int
     */
    public static function Stop(): int
    {
        self::$stop = microtime(true);

        return abs(
            Carbon::parse(self::$start)->format('s') - Carbon::parse(self::$stop)->format('s')
        );
    }
}
