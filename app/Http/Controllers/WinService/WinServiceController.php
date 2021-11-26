<?php

namespace App\Http\Controllers\WinService;

use App\Http\Controllers\Controller;
use App\Http\Resources\WinService\WinServiceResource;
use App\Repositories\WinServerRepository;
use App\Services\WinServiceService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WinServiceController extends Controller
{
    /**
     * @var WinServiceService
     */
    private WinServiceService $winServiceService;

    /**
     * @var WinServerRepository
     */
    private WinServerRepository $winServerRepository;

    /**
     * WinServiceController constructor.
     * @param WinServiceService $winServiceService
     * @param WinServerRepository $winServerRepository
     */
    public function __construct(
        WinServiceService $winServiceService,
        WinServerRepository $winServerRepository
    ) {
        $this->winServiceService = $winServiceService;
        $this->winServerRepository = $winServerRepository;
    }

    /**
     * @param int $id
     * @return Application|Factory|View
     */
    public function templateService(int $id)
    {
        return view('lk.windows.winservice.win_service', ['id' => $id]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $data = $request->all();

        $services = $this
            ->winServiceService
            ->getServicesByServerId($data);

        return response()->json([
            'data' => WinServiceResource::collection($services),
            'count' => $services->total()
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Set visible service for dashboard
     * @param Request $request
     * @return JsonResponse
     */
    public function setEnable(Request $request): JsonResponse
    {
        $service = $request->get('service');

        $res = $this
            ->winServiceService
            ->setEnableOrDisable($service);

        return response()->json(['data' => $res], JsonResponse::HTTP_OK);
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
