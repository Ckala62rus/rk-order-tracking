<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserRDP extends Model
{
    use HasFactory;

    protected $table = 'users_rdp';

    protected $fillable = [
        "login"
    ];
}
