<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRDP extends Model
{
    use HasFactory;

    protected $connection = "winapi";
    protected $table = "EventRDPs";

    protected $fillable = [
        'user',
        'current_date',
        'work_time',
    ];
}
