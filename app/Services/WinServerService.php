<?php

namespace App\Services;

use App\Repositories\WinServerRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class WinServerService
{
    /**
     * @var WinServerRepository
     */
    private WinServerRepository $winServerRepository;

    /**
     * WinServiceService constructor.
     * @param WinServerRepository $winServerRepository
     */
    public function __construct(WinServerRepository $winServerRepository)
    {
        $this->winServerRepository = $winServerRepository;
    }

    /**
     * Get all servers by paginate / limit 10
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function getServer($limit = 10): LengthAwarePaginator
    {
        return $this->winServerRepository->paginateAll($limit);
    }

    /**
     * Create new server
     * @param array $server
     * @return Model
     */
    public function createServer(array $server): Model
    {
        return $this
            ->winServerRepository
            ->store($server);
    }

    /**
     * Get record by id
     * @param int $id
     * @return Model|null
     */
    public function getRecordById(int $id): ?Model
    {
        return $this
            ->winServerRepository
            ->getRecord($id);
    }

    /**
     * Update server by id
     * @param array $data
     * @param int $id
     * @return Model
     */
    public function updateServer(array $data, int $id): Model
    {
        return $this
            ->winServerRepository
            ->update($data, $id);
    }

    /**
     * Delete server by id
     * @param int $id
     * @return bool
     */
    public function deleteServer(int $id): bool
    {
        return $this
            ->winServerRepository
            ->destroy($id);
    }

    /**
     * Enable or disable server
     * @param int $serverId
     * @return bool
     */
    public function setEnableOrDisable(int $serverId): bool
    {
        $server = $this
            ->winServerRepository
            ->getRecord($serverId);

        $server->enable = !$server->enable;
        return $server->save();
    }

    /**
     * Get server with relation services
     * @return Collection
     */
    public function getServices(): Collection
    {
        $query = $this
            ->winServerRepository
            ->query();

        $query = $this
            ->winServerRepository
            ->whereEnable($query);

        $query = $this
            ->winServerRepository
            ->getWithServicesInVisible($query);

        return $query->get();
    }
}
