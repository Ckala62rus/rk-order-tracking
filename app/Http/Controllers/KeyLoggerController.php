<?php

namespace App\Http\Controllers;

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

    public function detailGroupInformation(Request $request)
    {
        $filter = $request->all();

        $data = $this
            ->keyLoggerService
            ->getAllStatisticWithAggregation($filter)->get();

        // расчёт общего времени работы
        $workToday = $this
            ->keyLoggerService
            ->getWorkTimeToday($filter);

        $workTime = $this
            ->keyLoggerService
            ->calculateDateWorkTime($workToday);

//        $rows = KeyLoggerIndexResource::collection($data);

        // Convert resource to array
//        $activities = $rows->resolve();
        $activities = $data->toArray();
//dd($activities->toArray());
        $groupActivities = [];
//        dd($data);
        foreach ($activities as $activity) {
//            dd($activity);
            // если нет таколо ключа(логина), то добавляем модель в $groupActive
            $dateCreateEntity = Carbon::parse($activity["first_time"])->format("Y-m-d");
            $groupActivities[$activity["login"]][$dateCreateEntity][] = $activity;
        }

        $groupActivitiesSorted = [];
//dd($groupActivities);
//        foreach ($groupActivities as $login => $groupActivity) {
        foreach ($groupActivities as $login => $data) {
//            dd($login);
            foreach ($data as $year => $groupActivities) {
//                dd($year);
//                foreach ($groupActivities as $groupActivity) {
//dd(collect($groupActivities)->sortByDesc('id'));
                    // сортируем активности конкретного пользователя по id
                    $sortedGroupActivity = collect($groupActivities)->sortByDesc('id');

                    if ($sortedGroupActivity) {
//                        dd($groupActivity);
//                        $sorted = collect($groupActivity)->sortByDesc('id');
//                        dd($sorted);
//                        dd($sortedGroupActivity->values()->all());
//                        dd($login);
//                        dd($groupActivitiesSorted[$login][$year]);
                        $groupActivitiesSorted[$login][$year] = $sortedGroupActivity->values()->all();
                    }
//                }
            }
        }
//dd($groupActivitiesSorted);
        unset($groupActivities);

        $statistic = [];

        // извлекаем первый и последний элементы и заносим в новый массив
        foreach ($groupActivitiesSorted as $login => $date) {
            foreach ($date as $groupActivity) {
                $data = collect($groupActivity);

                if ($data->isNotEmpty()) {
                    if ($data->count() > 1) {

                        $first = $data->last();
                        $last = $data->first();

                        $statistic[$login][] = [
                            "login" => $first["login"],
//                            "fio" => $first["fio"],
                            "first_time" => $first["first_time"],
                            "last_active_time" => $last["last_active_time"],
//                            "department" => $last["department"],
//                            "organization" => $last["organization"],
                        ];
                    } else {
                        $statistic[$login][] = $data->first();
                    }
                }
            }
        }

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
