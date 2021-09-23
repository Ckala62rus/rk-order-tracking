<?php

namespace App\Repositories;

use App\Models\ArticleVersCustomersOrderDetail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class ArticleVersCustomersOrdersDetailRepository extends Repository
{
    /**
     * ArticleVersCustomersOrdersDetailRepository constructor.
     */
    public function __construct()
    {
        $this->model = new ArticleVersCustomersOrderDetail();
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
     * Find orders by AccountNum
     * @param Builder $query
     * @param string $accountId
     * @return Builder
     */
    public function whereAccountNum(Builder $query, string $accountId): Builder
    {
        return $query->where("AccountNum", $accountId);
    }

    /**
     * Find orders by ItemId
     * @param Builder $query
     * @param string $itemId
     * @return Builder
     */
    public function whereItemId(Builder $query, string $itemId): Builder
    {
        return $query->where("ItemId", $itemId);
    }

    /**
     * Filter by color field
     * @param Builder $query
     * @param string $color
     * @return Builder
     */
    public function whereColor(Builder $query, string $color): Builder
    {
        return $query->where('Color', 'like', '%' . $color . '%');
    }

    /**
     * Filter by realizationStatus
     * @param Builder $query
     * @param $status
     * @return Builder
     */
    public function whereRealizationStatus(Builder $query, $status): Builder
    {
        return $query->where("RealizationStatus", $status);
    }

    /**
     * Execute query builder and return orders collection
     * @param Builder $query
     * @return Collection
     */
    public function execute(Builder $query): Collection
    {
        return $query->get();
    }
}
