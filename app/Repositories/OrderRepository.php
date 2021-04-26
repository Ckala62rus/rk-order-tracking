<?php

namespace App\Repositories;

use App\Models\Orders;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository extends Repository
{
    /**
     * OrderRepository constructor.
     */
    public function __construct()
    {
        $this->model = new Orders();
    }

    /**
     * Create new query
     * @return Builder
     */
    public function query(): Builder
    {
        return $this
            ->model
            ->newQuery();
    }

    /**
     * Where company = id, from table users in MySQL
     * @param Builder $query
     * @param string $companyId
     * @return Builder
     */
    public function whereUser(Builder $query, string $companyId): Builder
    {
        return $query
            ->where('AccountNum', $companyId);
    }

    /**
     * Return data
     * @param Builder $query
     * @return Collection
     */
    public function executeAll(Builder $query): Collection
    {
        return $query
            ->limit(500) // for only test
            ->get();
    }

    /**
     * Get paginate
     * @param Builder $query
     * @param int $limit
     * @return LengthAwarePaginator
     */
    public function queryPaginate(Builder $query, int $limit): LengthAwarePaginator
    {
        return $query
            ->paginate($limit);
    }

    /**
     * Get all statuses
     * @return string[]
     */
    public function statuses(): array
    {
        return Orders::status();
    }

    /**
     * Filter by ProdOrderStatus field
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function whereStatus(Builder $query, string $status): Builder
    {
        return $query
            ->where('ProdOrderStatus', 'LIKE', $status . '%');
    }

    /**
     * Return query for filter by date
     * @param Builder $query
     * @param string $date_from
     * @param string $date_to
     * @return Builder
     */
    public function filterByDate(Builder $query, string $date_from, string $date_to): Builder
    {
        return $query
            ->where('DeliveryDate', '>=', $date_from)
            ->where('DeliveryDate', '<=', $date_to . '00:00:00');
    }
}
