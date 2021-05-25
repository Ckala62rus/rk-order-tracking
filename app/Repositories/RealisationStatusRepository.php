<?php

namespace App\Repositories;

use App\Models\RealisationStatus;

class RealisationStatusRepository extends Repository
{
    /**
     * RealisationStatusRepository constructor.
     */
    public function __construct()
    {
        $this->model = new RealisationStatus();
    }
}
