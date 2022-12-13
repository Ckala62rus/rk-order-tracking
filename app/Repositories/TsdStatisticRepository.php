<?php

namespace App\Repositories;

use App\Models\TsdStatistic;

class TsdStatisticRepository extends Repository
{
    public function __construct()
    {
        $this->model = new TsdStatistic();
    }
}
