<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ArticleVersCustomersOrdersDetailRepository;
use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use App\Repositories\PrjVersRepository;
use App\Repositories\RealisationStatusRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class OrderService
{
    /**
     * @var OrderRepository
     */
    public OrderRepository $orderRepository;

    /**
     * @var OrderStatusRepository
     */
    public OrderStatusRepository $orderStatusRepository;

    /**
     * @var RealisationStatusRepository
     */
    public RealisationStatusRepository $realisationStatusRepository;

    /**
     * @var PrjVersRepository
     */
    public PrjVersRepository $prjVersRepository;

    /**
     * @var ArticleVersCustomersOrdersDetailRepository
     */
    public ArticleVersCustomersOrdersDetailRepository $articleVersCustomersOrdersDetailRepository;

    /**
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param OrderStatusRepository $orderStatusRepository
     * @param RealisationStatusRepository $realisationStatusRepository
     * @param PrjVersRepository $prjVersRepository
     * @param ArticleVersCustomersOrdersDetailRepository $articleVersCustomersOrdersDetailRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository,
        RealisationStatusRepository $realisationStatusRepository,
        PrjVersRepository $prjVersRepository,
        ArticleVersCustomersOrdersDetailRepository $articleVersCustomersOrdersDetailRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
        $this->realisationStatusRepository = $realisationStatusRepository;
        $this->prjVersRepository = $prjVersRepository;
        $this->articleVersCustomersOrdersDetailRepository = $articleVersCustomersOrdersDetailRepository;
    }

    /**
     * Get all orders
     * @return Collection
     */
    public function getOrders(): Collection
    {
        $query = $this
            ->orderRepository
            ->query();

        $query = $this
            ->orderRepository
            ->whereUser($query, '3797');

        return $this
            ->orderRepository
            ->executeAll($query);
    }

    /**
     * Get all order records for paginate
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getOrdersPaginate(array $data): LengthAwarePaginator
    {
        $limit = $data['limit'] ?? 500;

        $query = $this
            ->orderRepository
            ->query();

        $user = Auth::user();

        if (isset($data['status'])) {
            $query = $this
                ->filterByStatus($data['status'], $query);
        }

        if (isset($data['date_from']) && isset($data['date_to'])) {
            $query = $this
                ->orderRepository
                ->filterByDate($query, $data['date_from'], $data['date_to']);
        }

        if (isset($data['realisation_status'])) {
            $query = $this
                ->orderRepository
                ->whereRealisationStatus($query, $data['realisation_status']);
        }

        if (isset($data['article'])) {
            $query = $this
                ->orderRepository
                ->whereArticle($query, $data['article']);
        }

        if (isset($data['color'])) {
            $query = $this
                ->orderRepository
                ->whereColor($query, $data['color']);
        }

        if (isset($data['company_id']) && ( $user->is_admin == true || $user->is_manager == true)) {
            $query = $this
                ->orderRepository
                ->whereUser($query, $data['company_id']);
        } else {
            $query = $this
                ->orderRepository
                ->whereUser($query, $user->account_id);
        }

        return $query
            ->paginate($limit);
    }

    /**
     * Return adaptive data
     * @param LengthAwarePaginator $data
     * @return LengthAwarePaginator
     */
    public function dataAdapter(LengthAwarePaginator $data): LengthAwarePaginator
    {
        foreach ($data as $item) {
            $item->OrderQTY = round($item->OrderQTY, 2);
            $item->OrderConfirmQTY = round($item->OrderConfirmQTY, 2);
            $item->ProdOrderQTY = round($item->ProdOrderQTY, 2);
            $item->DeliveryDate = Carbon::parse($item->DeliveryDate)->format('d-m-Y');
            $item->EndDate = $item->EndDate ? Carbon::parse($item->EndDate)->format('d-m-Y') : '';
            $item->OrderDate = Carbon::parse($item->OrderDate)->format('d-m-Y');
            $item->OrderQTY = number_format($item->OrderQTY, 0, ',', ' ');
            $item->SumQtySpeciallSku = number_format($item->SumQtySpeciallSku, 0, ',', ' ');
            $item->ProdOrderQTY = number_format($item->ProdOrderQTY, 0, ',', ' ');
        }

        return $data;
    }

    /**
     * Get all statuses from orders table
     * @return Collection
     */
    public function getAllStatuses(): Collection
    {
        return $this
            ->orderStatusRepository
            ->all();
    }

    /**
     * Return query or null
     * @param string $status
     * @param Builder $query
     * @return Builder
     */
    public function filterByStatus(string $status, Builder $query): Builder
    {
        return $this
            ->orderRepository
            ->whereStatus($query, $status);
    }

    /**
     * Get all realisation statuses
     * @return Collection
     */
    public function getAllRealisationStatus(): Collection
    {
        return $this
            ->realisationStatusRepository
            ->all();
    }

    /**
     * Get detail information
     * @param string $orderId
     * @return LengthAwarePaginator
     */
    public function getDetailInformation(string $orderId): LengthAwarePaginator
    {
        $query = $this
            ->orderRepository
            ->query();

        $query = $this
            ->orderRepository
            ->whereOrderNumber($query, $orderId);

        return $query
            ->paginate(1000);
    }

    /**
     * Get companies
     * @return Collection
     */
    public function getCompanies(): Collection
    {
        $query = $this
            ->orderRepository
            ->query();

        $query = $this
            ->orderRepository
            ->getCompanies($query);

        return $query->get();
    }

    /**
     * Return zip information for orders
     * @param array $data
     * @return Collection
     */
    public function zipOrderInfo(array $data): Collection
    {
        $user = Auth::user();

        $query = $this
            ->prjVersRepository
            ->query();

        if (isset($data["date_from"]) && isset($data["date_to"])) {
            $query = $this->prjVersRepository->filterByDate($query, $data["date_from"], $data["date_to"]);
        }
        if (isset($data["realisation_status"])) {
            $query = $this->prjVersRepository->whereRealisationStatus($query, $data["realisation_status"]);
        }
        if (isset($data["article"])) {
            $query = $this->prjVersRepository->whereArticle($query, $data["article"]);
        }
        if (isset($data["color"])) {
            $query = $this->prjVersRepository->whereColor($query, $data["color"]);
        }

        if (isset($data['company_id']) && ( $user->is_admin == true || $user->is_manager == true)) {
            $query = $this
                ->prjVersRepository
                ->whereUser($query, $data['company_id']);
        } else {
            $query = $this
                ->prjVersRepository
                ->whereUser($query, $user->account_id);
        }

        return $query
            ->get();
    }

    /**
     * Get detail orders by filters
     * @param array $data
     * @return Collection
     */
    public function detailZipOrders(array $data): Collection
    {
        $order = $this
            ->prjVersRepository
            ->getRecordByRowId($data["order_id"]);

        $filters = [
            'order_number' => $order->OrderNumber,
            'article' => $order->Article,
            'color' => $order->Color,
            'config' => $order->Config,
            'thickness' => $order->Thickness,
        ];

        $query = $this
            ->orderRepository
            ->query();

        if (isset($filters["order_number"])){
            $query = $this
                ->orderRepository
                ->whereOrderNumber($query, $filters["order_number"]);
        }

        if (isset($filters["article"])){
            $query = $this
                ->orderRepository
                ->whereArticle($query, $filters["article"]);
        }

        if (isset($filters["color"])){
            $query = $this
                ->orderRepository
                ->whereColor($query, $filters["color"]);
        }

        if (isset($filters["config"])){
            $query = $this
                ->orderRepository
                ->whereConfig($query, $filters["config"]);
        }

        if (isset($filters["thickness"])){
            $query = $this
                ->orderRepository
                ->whereThickness($query, $filters["thickness"]);
        }

        if (isset($data['status'])) {
            $query = $this
                ->filterByStatus($data['status'], $query);
        }

        if (isset($data['date_from']) && isset($data['date_to'])) {
            $query = $this
                ->orderRepository
                ->filterByDate($query, $data['date_from'], $data['date_to']);
        }

        if (isset($data['realisation_status'])) {
            $query = $this
                ->orderRepository
                ->whereRealisationStatus($query, $data['realisation_status']);
        }

        return $query->get();
    }

    /**
     * Adapter for zip order detail
     * @param Collection $data
     * @return Collection
     */
    public function adapterZipOrderDetail(Collection $data): Collection
    {
        foreach ($data as $item){
            $item->OrderDate = $item->OrderDate ? Carbon::parse($item->OrderDate)->format('d-m-Y') : '';
            $item->ProdOrderQTY = number_format($item->ProdOrderQTY, 0, ',', ' ');
            $item->DeliveryDate = $item->DeliveryDate ? Carbon::parse($item->DeliveryDate)->format('d-m-Y') : '';
            $item->EndDate = $item->EndDate ? Carbon::parse($item->EndDate)->format('d-m-Y') : '';
            $item->OrderedQTYPZ = number_format($item->OrderedQTYPZ, 0, ',', ' ');
            $item->SumQtyIzm = number_format($item->SumQtyIzm, 0, ',', ' ');
            $item->SumQtySales = number_format($item->SumQtySales, 0, ',', ' ');
        }

        return $data;
    }

    /**
     * Return order by filter article from
     * @param array $data
     * @return Collection
     */
    public function getOrdersByArticle(array $data): Collection
    {
        $orderRow = $this
            ->prjVersRepository
            ->getRecordByRowId($data["order_id"]);

        $query = $this
            ->articleVersCustomersOrdersDetailRepository
            ->query();

        $query = $this
            ->articleVersCustomersOrdersDetailRepository
            ->whereAccountNum($query, $orderRow->AccountNum);

        $query = $this
            ->articleVersCustomersOrdersDetailRepository
            ->whereItemId($query, $orderRow->ItemId);

        if (isset($data["color"])) {
            $query = $this
                ->articleVersCustomersOrdersDetailRepository
                ->whereColor($query, $data["color"]);
        }

        if (isset($data["realisation_status"])) {
            $query = $this
                ->articleVersCustomersOrdersDetailRepository
                ->whereRealizationStatus($query, $data["realisation_status"]);
        }

        return $this
            ->articleVersCustomersOrdersDetailRepository
            ->execute($query);
    }

    /**
     * Calculate volumes for orders
     * @param Collection $orders
     * @return int[]
     */
    public function calculateVolume(Collection $orders): array
    {
        $volumes = [
            "SumQtySpeciallSku" => 0, // Заказанный обьем
            "SumQtyIzm" => 0, // Измеренный обьем
            "SumQtySales" => 0, // Проданный обьем
        ];

        foreach ($orders as $order) {
            $volumes["SumQtySpeciallSku"] += (int)$order->SumQtySpeciallSku;
            $volumes["SumQtyIzm"] += (int)$order->SumQtyIzm;
            $volumes["SumQtySales"] += (int)$order->SumQtySales;
        }

        $volumes["SumQtySpeciallSku"] = number_format($volumes["SumQtySpeciallSku"], 0, ',', ' ');
        $volumes["SumQtyIzm"] = number_format($volumes["SumQtyIzm"], 0, ',', ' ');
        $volumes["SumQtySales"] = number_format($volumes["SumQtySales"], 0, ',', ' ');

        return $volumes;
    }
}
