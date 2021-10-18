<?php

namespace App\Repositories;

use App\Models\SigIn;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class SigInRepository extends Repository
{
    /**
     * SigInRepository constructor.
     */
    public function __construct()
    {
        $this->model = new SigIn();
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
     * Find record by field created_at
     * @param Builder $query
     * @param string $email
     * @return Builder
     */
    public function getRecordByDate(Builder $query, string $email): Builder
    {
        $now = Carbon::now()->format("Y-m-d");

        return $query
            ->where("created_at", "LIKE", $now . "%")
            ->where("email", $email);
    }

    /**
     * Execute build query and return model or null
     * @param Builder $query
     * @return Model|null
     */
    public function execute(Builder $query): ?Model
    {
        return $query->first();
    }
}
