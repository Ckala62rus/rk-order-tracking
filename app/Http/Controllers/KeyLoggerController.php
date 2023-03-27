<?php

namespace App\Http\Controllers;

use App\Http\Resources\KeyLogger\KeyLoggerIndexResource;
use App\Services\KeyLoggerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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
