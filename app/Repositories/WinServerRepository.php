<?php

namespace App\Repositories;

use App\Models\WinServer;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class WinServerRepository extends Repository
{
    /**
     * WinServerRepository constructor.
     */
    public function __construct()
    {
        $this->model = new WinServer();
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
     * Get relation with services
     * @param Builder $query
     * @return Builder
     */
    public function getWithServices(Builder $query): Builder
    {
        return $query->with('services');
    }

    /**
     * Get services by server id
     * @param Builder $query
     * @param int $server_id
     * @return Builder
     */
    public function whereServer(Builder $query, int $server_id): Builder
    {
        return $query->where('id', $server_id);
    }

    /**
     * Where services visible = true
     * @param Builder $query
     * @return Builder
     */
    public function whereEnable(Builder $query): Builder
    {
        return $query->where('enable', true);
    }

    /**
     * Get relation with services in visible status
     * @param Builder $query
     * @return Builder
     */
    public function getWithServicesInVisible(Builder $query): Builder
    {
        return $query->with('services', function($query){
            $query->where('visible', true);
        });
    }
}
