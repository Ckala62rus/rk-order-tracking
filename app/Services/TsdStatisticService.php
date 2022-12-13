<?php

namespace App\Services;

use App\Repositories\TsdStatisticRepository;

class TsdStatisticService
{
    /**
     * @var TsdStatisticRepository
     */
    private TsdStatisticRepository $tsdStatisticRepository;

    /**
     * @param TsdStatisticRepository $tsdStatisticRepository
     */
    public function __construct(TsdStatisticRepository $tsdStatisticRepository)
    {
        $this->tsdStatisticRepository = $tsdStatisticRepository;
    }

    /**
     * Create statistic ow in database
     * @param string $command
     * @return void
     */
    public function createTsdStatistic(string $command)
    {
        $this
            ->tsdStatisticRepository
            ->store(['command' => $command]);
    }
}
