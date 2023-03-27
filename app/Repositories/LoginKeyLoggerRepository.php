<?php

namespace App\Repositories;

use App\Models\LoginKeyLogger;
use Illuminate\Database\Eloquent\Builder;

class LoginKeyLoggerRepository extends Repository
{
    public function __construct()
    {
        $this->model = new LoginKeyLogger();
    }

    /**
     * Create new query
     * @return Builder
     */
    public function query(): Builder
    {
        return $this
            ->model
            ->newQuery();
    }
}
