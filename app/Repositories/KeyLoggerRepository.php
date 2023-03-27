<?php

namespace App\Repositories;

use App\Models\KeyLogger;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class KeyLoggerRepository extends Repository
{
    public function __construct()
    {
        $this->model = new KeyLogger();
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
     * @param Builder $query
     * @param array $data
     * @return Builder
     */
    public function getStatisticWithCurrentDate(Builder $query, array $data = []): Builder
    {
        $currentDate = Carbon::now()->format('d.m.Y');
        return $query->where('first_time', 'LIKE', $currentDate . '%');
    }
}
