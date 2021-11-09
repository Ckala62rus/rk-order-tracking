<?php

namespace App\Services;

use App\Repositories\StatisticRdpRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class RdpStatisticService
{
    /**
     * @var StatisticRdpRepository
     */
    private StatisticRdpRepository $statisticRdpRepository;

    /**
     * RdpStatisticService constructor.
     * @param StatisticRdpRepository $statisticRdpRepository
     */
    public function __construct(StatisticRdpRepository $statisticRdpRepository)
    {
        $this->statisticRdpRepository = $statisticRdpRepository;
    }

    /**
     * Get statistics with paginate
     * @param array $data
     * @return LengthAwarePaginator
     */
    public function getStatistics(array $data): LengthAwarePaginator
    {
        $query = $this
            ->statisticRdpRepository
            ->query();

        if (isset($data['date_start'], $data['date_end'])) {
            $query->whereBetween('date', [$data['date_start'], $data['date_end']]);
        }

        if (isset($data['login'])) {
            $query->where('login', $data['login']);
        }

        $query = $query
            ->orderBy('date', 'desc');

        return $query
            ->paginate($data['limit']);
    }
}
