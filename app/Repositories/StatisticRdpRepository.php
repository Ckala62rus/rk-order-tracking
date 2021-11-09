<?php

namespace App\Repositories;

use App\Models\StatisticRDP;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class StatisticRdpRepository extends Repository
{
    /**
     * StatisticRdpRepository constructor.
     */
    public function __construct()
    {
        $this->model = new StatisticRDP();
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        return $this->model->newQuery();
    }

    public function whereDate(Builder $query, string $date): Builder
    {
        return $query->where('date', '>=', $date);
    }

    public function whereUser(Builder $query, string $user): Builder
    {
        return $query->where('user', $user);
    }
}
