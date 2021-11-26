<?php

namespace App\Repositories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;

class WinServiceRepository extends Repository
{
    /**
     * WinServiceRepository constructor.
     */
    public function __construct()
    {
        $this->model = new Service();
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
     * Get all service by server name
     * @param Builder $query
     * @param string $server
     * @return Builder
     */
    public function whereServer(Builder $query, string $server): Builder
    {
        return $query->where('server', $server);
    }

    /**
     * Find services by server id
     * @param Builder $query
     * @param int $serverId
     * @return Builder
     */
    public function whereServerId(Builder $query, int $serverId): Builder
    {
        return $query->where('server_id', $serverId);
    }
}
