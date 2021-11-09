<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatisticRDP extends Model
{
    use HasFactory;

    protected $table = 'statistic_rdps';

    protected $fillable = [
        'login',
        'date',
        'work_time',
    ];
}
