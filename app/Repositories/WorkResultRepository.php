<?php

namespace App\Repositories;

use App\Models\WorkResult;
use Illuminate\Database\Eloquent\Builder;

class WorkResultRepository extends Repository
{
    /**
     * WorkResultRepository constructor.
     */
    public function __construct()
    {
        $this->model = new WorkResult();
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

    /**
     * Find statistics row by Property_run field
     * @param Builder $query
     * @param string $login
     * @return Builder
     */
    public function whereLogin(Builder $query, string $login): Builder
    {
        return $query->where('Property_run', $login);
    }

    /**
     * Find statistics row by Date_run field
     * @param Builder $query
     * @param string $date
     * @return Builder
     */
    public function whereDate(Builder $query, string $date): Builder
    {
        return $query->where('Date_run', $date . ' 00:00:00.000');
    }
}
