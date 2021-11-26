<?php

namespace App\Http\Controllers\WinService;

use App\Api\WindowsService\WindowsServiceApi;
use App\Http\Controllers\Controller;
use App\Http\Requests\WinService\GetServerWithServicesRequest;
use App\Http\Requests\WinService\WinServerCreateRequest;
use App\Http\Requests\WinService\WinServerRequest;
use App\Http\Resources\WinService\WinServerResource;
use App\Http\Resources\WinService\WinServerWithServiceResource;
use App\Http\Resources\WinService\WinServiceResource;
use App\Services\WinServerService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class WinServerController extends Controller
{
    /**
     * @var WinServerService
     */
    private WinServerService $winServerService;

    /**
     * @var WindowsServiceApi
     */
    private WindowsServiceApi $windowsServiceApi;

    /**
     * WinServiceController constructor.
     * @param WinServerService $winServerService
     * @param WindowsServiceApi $windowsServiceApi
     */
    public function __construct(
        WinServerService $winServerService,
        WindowsServiceApi $windowsServiceApi
    ) {
        $this->winServerService = $winServerService;
        $this->windowsServiceApi = $windowsServiceApi;
    }

    /**
     * @return Application|Factory|View
     */
    public function templateServer()
    {
        return view('lk.windows.winserver.win_server');
    }

    /**
     * @return Application|Factory|View
     */
    public function templateDashboard()
    {
        return view('lk.windows.dashboard');
    }

    /**
     * Get all servers with pagination
     * @param WinServerRequest $request
     * @return JsonResponse
     */
    public function index(WinServerRequest $request): JsonResponse
    {
        $limit = $request->get('limit') ?? 10;

        $servers = $this
            ->winServerService
            ->getServer($limit);

        return response()->json([
            'data' => WinServerResource::collection($servers),
            'count' => $servers->total(),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Create new server
     * @param WinServerCreateRequest $request
     * @return JsonResponse
     */
    public function store(WinServerCreateRequest $request)
    {
        $data = $request->all();

        $server = $this
            ->winServerService
            ->createServer($data);

        return response()->json([
            'data' => WinServiceResource::make($server)
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Get record by id
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $model = $this
            ->winServerService
            ->getRecordById($id);

        return response()->json([
            'data' => WinServiceResource::make($model)
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Update server by data and id
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $data = $request->all();

        $model = $this
            ->winServerService
            ->updateServer($data, $id);

        return response()->json([
            'data' => WinServiceResource::make($model)
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Destroy server by id
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $isDelete = $this
            ->winServerService
            ->deleteServer($id);

        return response()->json(['delete' => $isDelete], JsonResponse::HTTP_OK);
    }

    /**
     * Enable or disable server
     * @param Request $request
     * @return JsonResponse
     */
    public function setEnable(Request $request): JsonResponse
    {
        $server = $request->get('server');

        $res = $this
            ->winServerService
            ->setEnableOrDisable($server);

        return response()->json(['data' => $res], JsonResponse::HTTP_OK);
    }

    /**
     * Get all services by server id
     * @return JsonResponse
     */
    public function getServices(): JsonResponse
    {
        $res = $this
            ->winServerService
            ->getServices();

        return response()->json([
            'data' => WinServerWithServiceResource::collection($res),
            'count' => count($res)
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Execute command for update services
     * @return JsonResponse
     */
    public function updateInfoByServers()
    {
        Artisan::call('command:WinServiceUpdate');

        return response()->json([], JsonResponse::HTTP_OK);
    }

    /**
     * Start service by server name
     * @param Request $request
     * @return JsonResponse
     */
    public function startService(Request $request): JsonResponse
    {
        $data = $request->all();

        $service = $this
            ->windowsServiceApi
            ->startService($data['server'], $data['service']);

        return response()->json(['data' => $service], JsonResponse::HTTP_OK);
    }

    /**
     * Stop service by server name
     * @param Request $request
     * @return JsonResponse
     */
    public function stopService(Request $request): JsonResponse
    {
        $data = $request->all();

        $service = $this
            ->windowsServiceApi
            ->stopService($data['server'], $data['service']);

        return response()->json(['data' => $service], JsonResponse::HTTP_OK);
    }
}
