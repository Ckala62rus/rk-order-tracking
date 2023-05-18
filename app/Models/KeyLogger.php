<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyLogger extends Model
{
    use HasFactory;

    protected $connection = "keylogger";
    protected $table = "Statistic";

    protected $fillable = [
        'login',
        'first_time',
        'last_active_time',
    ];

    public function details()
    {
        return $this->hasMany(
            KeyLoggerStatistic::class,
            'session_id',
            'id'
        );
    }

    public function activeWindowsSeconds()
    {
        return $this->hasMany(
            KeyLoggerActiveWindow::class,
            'session_id',
            'id'
        );
    }
}
