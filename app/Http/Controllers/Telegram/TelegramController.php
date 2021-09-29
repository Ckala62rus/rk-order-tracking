<?php

namespace App\Http\Controllers\Telegram;

use App\Http\Controllers\Controller;
use App\Services\WarehouseService;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * @var WarehouseService
     */
    private WarehouseService $warehouseService;

    /**
     * TelegramController constructor.
     * @param WarehouseService $warehouseService
     */
    public function __construct(WarehouseService $warehouseService)
    {
        $this->warehouseService = $warehouseService;
    }

    /**
     * Get telegram callback api
     * @param Request $request
     */
    public function callbackTelegramApi(Request $request): void
    {
        $data = $request->all();

        $params = [
            'username' => Arr::get($data, 'message.from.username'),
            'first_name' => Arr::get($data, 'message.from.first_name'),
            'user_id' => Arr::get($data, 'message.from.id'),
            'text_in' => Arr::get($data, 'message.text'),

//            'user_id' => 803431360,
//            'text_in' => 12345,
//            'text_in' => '/help',
//            'text_in' => '/adduser@557327331',
//            'text_in' => '/help',
        ];

        Log::info($data);

        $this
            ->warehouseService
            ->getRouteCommand($params);
    }
}
