<?php

namespace App\Http\Controllers\Lk;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderDetailRequest;
use App\Http\Requests\Order\SimpleOrderRequest;
use App\Http\Requests\Order\ZipOrderDetailRequest;
use App\Http\Resources\Order\OrderResource;
use App\Http\Resources\Order\SimpleOrderResource;
use App\Http\Resources\Order\ZipOrderDetailGroupResource;
use App\Http\Resources\OrderStatus\StatusResource;
use App\Http\Resources\RealisationStatus\RealisationStatusResource;
use App\Mail\TestMail;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

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
            'data' => StatusResource::collection($statuses)
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Get all realisation statuses
     * @return JsonResponse
     */
    public function getRealisationStatuses(): JsonResponse
    {
        $realisationStatuses = $this
            ->orderService
            ->getAllRealisationStatus();

        return response()->json([
            'data' => RealisationStatusResource::collection($realisationStatuses)
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Get detail information for modal window
     * @param OrderDetailRequest $request
     * @return JsonResponse
     */
    public function getDetailInformationFromModal(OrderDetailRequest $request): JsonResponse
    {
        $orderId = $request->only("order_number");

        $orders = $this
            ->orderService
            ->getDetailInformation($orderId["order_number"]);

        $data = $this
            ->orderService
            ->dataAdapter($orders);

        return response()->json([
            'data' => OrderResource::collection($data),
            'count' => $data->total(),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Get companies
     * @return JsonResponse
     */
    public function getCompanies(): JsonResponse
    {
        $companies = $this
            ->orderService
            ->getCompanies();

        return response()->json([
            'companies' => $companies,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Return zip order for company
     * @param SimpleOrderRequest $request
     * @return JsonResponse
     */
    public function newOrder(SimpleOrderRequest $request): JsonResponse
    {
        $data = $request->all();

        $orders = $this
            ->orderService
            ->zipOrderInfo($data);

        $calculateVolume = $this
            ->orderService
            ->calculateVolume($orders);

        return response()->json([
            'data' => SimpleOrderResource::collection($orders),
//            'data' => $orders,
            'count' => count($orders),
            'volumes' => $calculateVolume,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Return zip detail orders by Row# id
     * @param ZipOrderDetailRequest $request
     * @return JsonResponse
     */
    public function getDetailZipOrders(ZipOrderDetailRequest $request): JsonResponse
    {
        $data = $request->all();

        $orders = $this
            ->orderService
            ->detailZipOrders($data);

        $orders = $this
            ->orderService
            ->adapterZipOrderDetail($orders);

        return response()->json([
            'data' => $orders,
            'count' => count($orders),
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Return orders by article from ArticleVersCustomersOrdersDetail table
     * @param ZipOrderDetailRequest $request
     * @return JsonResponse
     */
    public function getDetailZipOrderByGroup(ZipOrderDetailRequest $request): JsonResponse
    {
        $data = $request->all();

        $orders = $this
            ->orderService
            ->getOrdersByArticle($data);

        return response()->json([
            'data' => ZipOrderDetailGroupResource::collection($orders),
            'count' => count($orders),
        ], JsonResponse::HTTP_OK);
    }
}
