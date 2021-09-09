<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrdersSeed extends Model
{
    use HasFactory;

//    protected $table = 'orders';
    protected $table = "prodorderstatus";
    public $timestamps = false;

//    protected $fillable = [
//        "OrderNumber",
//        "ProdOrderNumber",
//        "OrderQTY",
//        "OrderDate",
//        "OrderConfirmQTY",
//        "ContractorName",
//        "ManagerName",
//        "Article",
//        "ProdOrderStatus",
//        "ProdOrderQTY",
//        "Color",
//        "Config",
//        "Thickness",
//        "DeliveryDate",
//        "EndDate",
//        "LeadOrLagTime",
//        "AccountNum",
//        "ContractorNameActual",
//        "Prodstatus",
//        "IDProdOrderStatus",
//    ];

    protected $fillable = [
        'ProdOrderStatus',
        'IDProdOrderStatus',
    ];
}
