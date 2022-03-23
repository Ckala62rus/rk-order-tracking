<?php

namespace App\Http\Controllers;

use App\Services\TsdService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Mockery\Exception;

class TsdController extends Controller
{
    /**
     * @var TsdService
     */
    private TsdService $tsdService;

    /**
     * TsdController constructor.
     * @param TsdService $tsdService
     */
    public function __construct(TsdService $tsdService)
    {
        $this->tsdService = $tsdService;
    }

    /**
     * Api method for find part
     * @param Request $request
     * @return JsonResponse
     */
    public function findPart(Request $request): JsonResponse
    {
        $data = $request->only('code');

        try {
            $result = $this->tsdService->getRouteCommand($data['code']);
        } catch (Exception $exception) {
            return ['error' => $exception->getMessage()];
        }

        return response()->json($result, JsonResponse::HTTP_OK);
    }

    public function form()
    {
        return view('tsd.index');
    }
}
