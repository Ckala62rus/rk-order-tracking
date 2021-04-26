<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use App\Http\Resources\Order\OrderResource;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderApiController extends Controller
{
    /**
     * @var OrderService
     */
    public OrderService $orderService;

    /**
     * OrderController constructor.
     * @param OrderService $orderService
     */
    public function __construct(
        OrderService $orderService
    ) {
        $this->orderService = $orderService;
    }

    /**
     * Return orders for company
     * @param Request $request
     * @return JsonResponse
     */
    public function getOrders(Request $request): JsonResponse
    {
        $data = $request->all();

        $rawData = $this
            ->orderService
            ->getOrdersPaginate($data);

        $data = $this
            ->orderService
            ->dataAdapter($rawData);

        return response()->json([
            'data' => OrderResource::collection($data),
            'count' => $data->total(),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Get all statuses
     * @return JsonResponse
     */
    public function getStatuses(): JsonResponse
    {
        $statuses = $this
            ->orderService
            ->getAllStatuses();

        return response()->json([
            'data' => $statuses
        ]);
    }
}
