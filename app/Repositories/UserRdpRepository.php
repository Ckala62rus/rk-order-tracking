<?php

namespace App\Repositories;

use App\Models\UserRDP;
use Illuminate\Database\Eloquent\Builder;


class UserRdpRepository extends Repository
{
    public function __construct()
    {
        $this->model = new UserRDP();
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
     * Find user by login
     * @param Builder $query
     * @param string $login
     * @return Builder
     */
    public function whereLogin(Builder $query, string $login): Builder
    {
        return $query->where('login', $login);
    }
}
