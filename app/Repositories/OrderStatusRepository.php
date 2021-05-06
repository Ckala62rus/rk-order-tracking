<?php

namespace App\Repositories;

use App\Models\ProdOrderStatus;

class OrderStatusRepository extends Repository
{
    /**
     * OrderRepository constructor.
     */
    public function __construct()
    {
        $this->model = new ProdOrderStatus();
    }
}
