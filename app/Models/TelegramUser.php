<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramUser extends Model
{
    protected $fillable = [
        'first_name',
        'username',
        'telegram_user_id',
        'is_admin',
        'is_active',
    ];
}
