<?php

namespace App\Http\Controllers;

use App\Exports\LoginsAggregateStatisticExport;
use App\Exports\LoginsExport;
use App\Http\Resources\KeyLogger\KeyLoggerIndexResource;
use App\Services\KeyLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Maatwebsite\Excel\Facades\Excel;

class KeyLoggerController extends Controller
{
    /**
     * @var KeyLoggerService
     */
    private KeyLoggerService $keyLoggerService;

    public function __construct(KeyLoggerService $keyLoggerService)
    {
        $this->keyLoggerService = $keyLoggerService;
    }

    public function templateDashboard()
    {
        return view('lk.key-logger.index');
    }

    public function index(Request $request)
    {
        $filter = $request->all();

        $data = $this
            ->keyLoggerService
            ->getAllStatisticWithRelation($filter);

        // расчёт общего времени работы
        $workToday = $this
            ->keyLoggerService
            ->getWorkTimeToday($filter);

        $workTime = $this
            ->keyLoggerService
            ->calculateDateWorkTime($workToday);

        return response()->json([
            'data' => KeyLoggerIndexResource::collection($data),
            'count' => $data->total(),
            'workTime' => $workTime,
        ]);
    }

    public function getDetailInformationByLogin(Request $request)
    {
        $filter = $request->all();

        $data = $this
            ->keyLoggerService
            ->getAllStatisticWithAggregation($filter)->get();

        $result = $this
            ->keyLoggerService
            ->getWindowActivitiesForAggregateStatistic($data);

        return response()->json([
            'data' => $result,
        ]);
    }

    public function detailGroupInformation(Request $request)
    {
        $filter = $request->all();

        $data = $this
            ->keyLoggerService
            ->getAllStatisticWithAggregation($filter)->get();
//dd($data->toArray());
        // сортируем стату по логинам
        $res = $this
            ->keyLoggerService
            ->sortActivitiesByLogin($data);

        // расчёт общего времени работы для каждого пользователя
        $workTime = $this
            ->keyLoggerService
            ->calculateWorkTimeEachUser($res);

        $activities = $data->toArray();

        // сортируем активности пользователей по логину и дате
        $groupActivities = $this
            ->keyLoggerService
            ->aggregationStatistic($activities);

        $groupActivitiesSorted = $this
            ->keyLoggerService
            ->sortAggregationStatisticById($groupActivities);

        unset($groupActivities);

        $statistic = $this
            ->keyLoggerService
            ->retrieveFirstAndLastActivityElements($groupActivitiesSorted);

        unset($groupActivitiesSorted);

        return response()->json([
            'data' => $statistic,
            'workTime' => $workTime,
        ]);
    }

    public function export(Request $request)
    {
        return Excel::download(new LoginsExport($request, $this->keyLoggerService), 'logins.xlsx');
    }

    public function exportAggregate(Request $request)
    {
        return Excel::download(new LoginsAggregateStatisticExport($request, $this->keyLoggerService), 'logins.xlsx');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
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

    public function test(Request $request)
    {
//        $response = Soap::
//            baseWsdl('http://rk-iis-in/work_itilium/ws/RK_Web.1cws?wsdl')
//            ->withBasicAuth('WebServices', '1')
//            ->call('Test');
//
//        dd($response->body());
//        dump($request->all());
//        dd('test');
        $url = "http://RK-IIS-IN:80/work_itilium/hs/RK_Web/GetEmployees";
        $response = Http::withBasicAuth('WebServices', '1')->get($url);
        dd($response->json());


    }
}
