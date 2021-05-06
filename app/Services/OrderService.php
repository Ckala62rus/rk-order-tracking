<?php

namespace App\Services;

use App\Repositories\OrderRepository;
use App\Repositories\OrderStatusRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

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
     * OrderService constructor.
     * @param OrderRepository $orderRepository
     * @param OrderStatusRepository $orderStatusRepository
     */
    public function __construct(
        OrderRepository $orderRepository,
        OrderStatusRepository $orderStatusRepository
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderStatusRepository = $orderStatusRepository;
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
        $limit = isset($data['limit']) ? $data['limit'] : 500;

        $query = $this
            ->orderRepository
            ->query();

        if (isset($data['status'])) {
            $query = $this
                ->filterByStatus($data['status'], $query);
        }

        if (isset($data['date_from']) && isset($data['date_to'])) {
            $query = $this
                ->orderRepository
                ->filterByDate($query, $data['date_from'], $data['date_to']);
        }

        $query = $this
            ->orderRepository
            ->whereUser($query, '3797');

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
            $item->EndDate = Carbon::parse($item->EndDate)->format('d-m-Y');
            $item->OrderDate = Carbon::parse($item->OrderDate)->format('d-m-Y');
            $item->OrderQTY = number_format($item->OrderQTY, 0, ',', ' ');
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

}
