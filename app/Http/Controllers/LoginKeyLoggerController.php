<?php

namespace App\Http\Controllers;

use App\Services\LoginKeyLoggerService;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

class LoginKeyLoggerController extends Controller
{
    public LoginKeyLoggerService $keyLoggerService;

    public function __construct(LoginKeyLoggerService $keyLoggerService)
    {
        $this->keyLoggerService = $keyLoggerService;
    }

    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = $this
            ->keyLoggerService
            ->getAllLogin();

        return response()->json([
            'users' => $users
        ], ResponseAlias::HTTP_OK);
    }
}
