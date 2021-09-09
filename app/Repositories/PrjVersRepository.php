<?php

namespace App\Repositories;

use App\Models\PrjVers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class PrjVersRepository extends Repository
{
    /**
     * PrjVersRepository constructor.
     */
    public function __construct()
    {
        $this->model = new PrjVers();
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
     * Where company = id, from table PrjVers in MS SQL
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
     * Filter by RealizationStatus field
     * @param Builder $query
     * @param string $status
     * @return Builder
     */
    public function whereRealisationStatus(Builder $query, string $status): Builder
    {
        return $query->where('RealizationStatus', $status);
    }

    /**
     * Filter by article
     * @param Builder $query
     * @param string $article
     * @return Builder
     */
    public function whereArticle(Builder $query, string $article): Builder
    {
        return $query->where('Article', 'like', '%' . $article . '%');
    }

    /**
     * Filter by color
     * @param Builder $query
     * @param string $color
     * @return Builder
     */
    public function whereColor(Builder $query, string $color): Builder
    {
        return $query->where('Color', 'like', '%' . $color . '%');
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
            ->where('DeliveryDate', '>=', $date_from . ' ' . '00:00:00.000')
            ->where('DeliveryDate', '<=', $date_to . ' ' . '23:59:00.000');
    }

    /**
     * Return one record by Row# id
     * @param string $row
     * @return Model
     */
    public function getRecordByRowId(string $row): Model
    {
        return $this
            ->query()
            ->where("Row#", $row)
            ->first();
    }
}
