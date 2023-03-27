<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KeyLoggerStatistic extends Model
{
    use HasFactory;

    protected $connection = "keylogger";
    protected $table = "SessionDetails";

    protected $fillable = [
        'session_id',
        'active_window',
        'date',
    ];
}
