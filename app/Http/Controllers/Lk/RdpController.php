<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Rdp\RdpDetailRequest;
use App\Http\Requests\Rdp\RdpRequest;
use App\Http\Resources\Rdp\RdpDetailResource;
use App\Http\Resources\Rdp\RdpResource;
use App\Http\Resources\Rdp\RdpUsersResource;
use App\Repositories\UserRdpRepository;
use App\Repositories\WorkResultRepository;
use App\Services\RdpStatisticService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RdpController extends Controller
{
    /**
     * @var RdpStatisticService
     */
    private RdpStatisticService $rdpStatisticService;

    /**
     * @var UserRdpRepository
     */
    private UserRdpRepository $userRdpRepository;

    /**
     * @var WorkResultRepository
     */
    private WorkResultRepository $workResultRepository;

    /**
     * RdpController constructor.
     * @param RdpStatisticService $rdpStatisticService
     * @param UserRdpRepository $userRdpRepository
     * @param WorkResultRepository $workResultRepository
     */
    public function __construct(
        RdpStatisticService $rdpStatisticService,
        UserRdpRepository $userRdpRepository,
        WorkResultRepository $workResultRepository
    ) {
        $this->rdpStatisticService = $rdpStatisticService;
        $this->userRdpRepository = $userRdpRepository;
        $this->workResultRepository = $workResultRepository;
    }

    public function index()
    {
        return view('lk.rdp.index');
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

    /**
     * Get statistic
     * @param RdpRequest $rdpRequest
     * @return JsonResponse
     */
    public function getStatistics(RdpRequest $rdpRequest): JsonResponse
    {
        $data = $rdpRequest->all();

        $statistics = $this
            ->rdpStatisticService
            ->getStatistics($data);

        return response()->json([
            'data' => RdpResource::collection($statistics),
            'count' => $statistics->total(),
        ]);
    }

    /**
     * Get all RDP users
     * @return JsonResponse
     */
    public function getUsers(): JsonResponse
    {
        $users = $this
            ->userRdpRepository
            ->all();

        return response()->json([
            'data' => RdpUsersResource::collection($users)
        ]);
    }

    /**
     * Get detail statistics by user
     * @param RdpDetailRequest $request
     * @return JsonResponse
     */
    public function getDetailByUser(RdpDetailRequest $request): JsonResponse
    {
        $data = $request->all();

        $query = $this
            ->workResultRepository
            ->query();

        $query = $this
            ->workResultRepository
            ->whereLogin($query, $data['login']);

        $query = $this
            ->workResultRepository
            ->whereDate($query, $data['date']);

        $query->orderBy('DayTime_run', 'asc');

        $result = $query->get();

        return response()->json([
            'data' => RdpDetailResource::collection($result)
        ]);
    }
}
