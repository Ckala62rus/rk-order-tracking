<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'server',
        'service_name',
        'display_name',
        'status',
        'visible',
        'server_id',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];
}
