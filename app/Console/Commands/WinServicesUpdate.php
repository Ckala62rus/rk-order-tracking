<?php

namespace App\Console\Commands;

use App\Api\WindowsService\WindowsServiceApi;
use App\Models\Orders;
use App\Models\WinServer;
use App\Services\WinServiceService;
use Illuminate\Config\Repository;
use Illuminate\Console\Command;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;
use Mockery\Exception;
use function PHPUnit\Framework\isEmpty;

class WinServicesUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WinServiceUpdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update windows service by server name';

    /**
     * @var string|Repository|Application|mixed
     */
    private string $baseUrl;

    /**
     * @var WindowsServiceApi
     */
    private WindowsServiceApi $windowsApi;

    /**
     * @var WinServiceService
     */
    private WinServiceService $winServiceService;

    /**
     * Create a new command instance.
     *
     * @param WinServiceService $winServiceService
     */
    public function __construct(WinServiceService $winServiceService)
    {
        parent::__construct();

        $this->baseUrl = config('windows_service.api_url_base') . 'winservice/';
        $this->windowsApi = new WindowsServiceApi();
        $this->winServiceService = $winServiceService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //dd(DB::connection('mysql')->getPdo());
        $servers = WinServer::all(['id', 'server_name', 'enable']);

        foreach ($servers as $server) {

            $services = $this
                ->windowsApi
                ->getAllWindowsServicesByServerName($server->server_name);

            if (isset($services['Error'])) {
                continue;
            }

            foreach ($services as $service) {
                $this
                    ->winServiceService
                    ->createOrUpdateService($server, $service);
            }
        }

        return Command::SUCCESS;
    }
}
