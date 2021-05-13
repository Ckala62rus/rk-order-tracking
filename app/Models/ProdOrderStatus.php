<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdOrderStatus extends Model
{
    use HasFactory;

    protected $connection = 'sqlsrv';
<<<<<<< HEAD
    protected $table = 'ProdOrderStatus';
=======
    protected $table = 'prodorderstatus';
>>>>>>> user_control
}
