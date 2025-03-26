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

        $activities = $data->toArray();

        $groupActivities = [];

        foreach ($activities as $activity) {
            // если нет таколо ключа(логина), то добавляем модель в $groupActive
            $dateCreateEntity = Carbon::parse($activity["first_time"])->format("Y-m-d");
            $groupActivities[$activity["login"]][$dateCreateEntity][] = $activity;
        }

        $groupActivitiesSorted = [];

        foreach ($groupActivities as $login => $data) {

            foreach ($data as $year => $groupActivities) {
                    // сортируем активности конкретного пользователя по id
                    $sortedGroupActivity = collect($groupActivities)->sortByDesc('id');

                    if ($sortedGroupActivity) {
                        $groupActivitiesSorted[$login][$year] = $sortedGroupActivity->values()->all();
                    }

            }
        }

        unset($groupActivities);

        $statistic = [];
        $userLogins = [];

        // извлекаем первый и последний элементы и заносим в новый массив
        foreach ($groupActivitiesSorted as $login => $date) {
            foreach ($date as $groupActivity) {
                $data = collect($groupActivity);

                if ($data->isNotEmpty()) {
                    if ($data->count() > 1) {

                        $first = $data->last();
                        $last = $data->first();

                        if (!array_key_exists($first["login"], $userLogins)) {
                            $userModel = $this->keyLoggerService->getModel($first["login"]);
                            $userLogins[$first["login"]] = $userModel ?? null;
                        }

                        $statistic[$login][] = [
                            "id" => $first["id"],
                            "login" => $first["login"],
                            "fio" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->fio : "Отсутствует в 1С",
                            "first_time" => $first["first_time"],
                            "last_active_time" => $last["last_active_time"],
                            "department" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->department : "Отсутствует в 1С",
                            "organization" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->organization : "Отсутствует в 1С",
                        ];
                    } else {
                        $first = $data->first();

                        if (!array_key_exists($first["login"], $userLogins)) {
                            $userModel = $this->keyLoggerService->getModel($first["login"]);
                            $userLogins[$first["login"]] = $userModel ?? null;
                        }

                        $statistic[$login][] = [
                            "id" => $first["id"],
                            "login" => $first["login"],
                            "fio" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->fio : "Отсутствует в 1С",
                            "first_time" => $first["first_time"],
                            "last_active_time" => $last["last_active_time"],
                            "department" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->department : "Отсутствует в 1С",
                            "organization" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->organization : "Отсутствует в 1С",
                        ];
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
