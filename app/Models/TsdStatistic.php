<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TsdStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'command'
    ];
}
