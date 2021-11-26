<?php

namespace App\Services;

use App\Models\Service;
use App\Models\WinServer;
use App\Repositories\WinServiceRepository;
use Illuminate\Config\Repository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class WinServiceService
{
    /**
     * @var WinServiceRepository
     */
    private WinServiceRepository $winServiceResource;

    /**
     * @var string|Repository|Application|mixed
     */
    private string $baseUrl;

    /**
     * WinServiceService constructor.
     * @param WinServiceRepository $winServiceResource
     */
    public function __construct(WinServiceRepository $winServiceResource)
    {
        $this->winServiceResource = $winServiceResource;
        $this->baseUrl = config('windows_service.api_url_base');
    }

    /**
     * Create or update record (service) by server name and service name
     * @param WinServer $server
     * @param array $data
     * @return Model|null
     */
    public function createOrUpdateService(WinServer $server, array $data): ?Model
    {
        return Service::updateOrCreate(
            [
                'server_id' => $server->id,
                'server' => $server->server_name,
                'service_name' => $data['ServiceName'],
            ],
            [
                'server_id' => $server->id,
                'server' => $server->server_name,
                'service_name' => $data['ServiceName'],
                'display_name' => $data['DisplayName'],
                'status' => $data['Status'],
            ]
        );
    }

    /**
     * Get all service by server name
     * @param string $server
     * @return Collection
     */
    public function getServiceByServer(string $server): Collection
    {
        $query = $this
            ->winServiceResource
            ->query();

        return $this
            ->winServiceResource
            ->whereServer($query, $server)
            ->get();
    }

    /**
     * Get all services by server id
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getServicesByServerId(array $data): LengthAwarePaginator
    {
        $query = $this
            ->winServiceResource
            ->query();

        return $this
            ->winServiceResource
            ->whereServerId($query, $data['server_id'])
            ->paginate($data['limit']);
    }

    /**
     * Set visible service for dashboard
     * @param int $serviceId
     * @return bool
     */
    public function setEnableOrDisable(int $serviceId): bool
    {
        $server = $this
            ->winServiceResource
            ->getRecord($serviceId);

        $server->visible = !$server->visible;
        return $server->save();
    }
}
