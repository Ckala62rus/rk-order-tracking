<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

//    protected $connection = "sqlsrv";
//    protected $table = "CustomersOrdersDetail";

//    protected $perPage = 2;

    /**
     * Get all statuses from select component
     * @return string[]
     */
    public static function status(): array
    {
        return [
            ['id' => 0, 'status' => "Оценка производственных ресурсов"],
            ['id' => 1, 'status' => "Запланировано к производству"],
            ['id' => 2, 'status' => "Производство начато"],
            ['id' => 3, 'status' => "Производство завершено"],
        ];
    }

    /**
     * Return statuses from filter
     * @return array[]
     */
    public static function statusForFilter(): array
    {
        return [
            ['id' => 1, 'status' => "Оценка производственных ресурсов"],
            ['id' => 2, 'status' => "Запланировано к производству"],
            ['id' => 3, 'status' => "Запланировано к производству"],
            ['id' => 4, 'status' => "Производство начато"],
            ['id' => 5, 'status' => "Производство завершено"],
            ['id' => 7, 'status' => "Производство завершено"],
        ];
    }
}
