<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SigIn extends Model
{
    use HasFactory;

    protected $table = "sigin_log";

    protected $fillable = [
        "email"
    ];
}
