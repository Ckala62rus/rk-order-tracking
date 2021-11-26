<?php

namespace App\Api\WindowsService;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class WindowsServiceApi
{
    /**
     * Base api windows services url
     * @var string
     */
    private string $baseUrl;

    /**
     * @var PendingRequest
     */
    private PendingRequest $client;

    /**
     * Get all services by this url
     * example : GetServices?Server=rk-zm3-prd
     * @var string
     */
    private string $allServices = 'GetServices?Server=';

    /**
     * WindowsServiceApi constructor.
     */
    public function __construct()
    {
        $this->baseUrl = config('windows_service.api_url_base') . 'winservice/';
        $this->client = Http::withoutVerifying();
    }

    /**
     * Get all windows services by server name
     * @param string $server
     * @return Collection
     */
    public function getAllWindowsServicesByServerName(string $server): Collection
    {
        return  $this
            ->client
            ->get($this->baseUrl .  $this->allServices . $server)
            ->collect();
    }

    /**
     * Get one service by server name
     * @param string $server
     * @param string $service
     * @return Collection
     */
    public function getServiceByServerName(string $server, string $service): Collection
    {
        return  $this
            ->client
            ->get($this->baseUrl . "GetServiceByName?ServiceName=" . $service . "&Server=" . $server)
            ->collect();
    }

    /**
     * Start service by server and service name
     * @param string $server
     * @param string $service
     * @return Collection
     */
    public function startService(string $server, string $service): Collection
    {
        return  $this
            ->client
            ->get($this->baseUrl . "StartService?ServiceName=" . $service . "&Server=" . $server)
            ->collect();
    }

    /**
     * Stop service by server and service name
     * @param string $server
     * @param string $service
     * @return Collection
     */
    public function stopService(string $server, string $service): Collection
    {
        return  $this
            ->client
            ->get($this->baseUrl . "StopService?ServiceName=" . $service . "&Server=" . $server)
            ->collect();
    }
}
