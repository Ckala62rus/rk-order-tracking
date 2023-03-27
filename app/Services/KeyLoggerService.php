<?php

namespace App\Services;

use App\Repositories\KeyLoggerRepository;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class KeyLoggerService
{
    /**
     * @var KeyLoggerRepository
     */
    private KeyLoggerRepository $keyLoggerRepository;

    /**
     * @var LoginKeyLoggerService
     */
    private LoginKeyLoggerService $keyLoggerService;

    /**
     * @param KeyLoggerRepository $keyLoggerRepository
     */
    public function __construct(
        KeyLoggerRepository $keyLoggerRepository,
        LoginKeyLoggerService $keyLoggerService
    ) {
        $this->keyLoggerRepository = $keyLoggerRepository;
        $this->keyLoggerService = $keyLoggerService;
    }

    /**
     * Get distinct user login for save in db
     * @return Builder[]|Collection
     */
    public function getDistinctLogin()
    {
        $query = $this->keyLoggerRepository->query();
        $query->select(['login']);
        $query->groupBy('login');
        return $query->get();
    }

    /**
     * Get statistic details with relation
     * @param array $filter
     * @return LengthAwarePaginator
     */
    public function getAllStatisticWithRelation(array $filter): LengthAwarePaginator
    {
        $query = $this->keyLoggerRepository->query();

        if (isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where(
                'first_time',
                'LIKE',
                Carbon::parse($filter['date_start'])->format('d.m.Y') . '%'
            );
        }

        if (isset($filter['date_start']) && isset($filter['date_end'])){
            $query->where('first_time', '>=',  Carbon::parse($filter['date_start'])->format('d.m.Y') . ' 00:00:00');
            $query->where('first_time', '<=',  Carbon::parse($filter['date_end'])->addDays(1)->format('d.m.Y') . ' ' . '23:59:59');
        }

        if (!isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where('first_time', 'LIKE', Carbon::now()->format('d.m.Y') . '%');
        }

        if(isset($filter['login'])){
            $user = $this->keyLoggerService->getUserById($filter['login']);

            $query->where('login', 'LIKE', '%'.$user->login.'%');
        }

        $query->with(['details' => function($q){
            $q->select(
                DB::raw('max(session_id) as session_id'),
                DB::raw('active_window'),
                DB::raw('max(date) as date'),
            );
            $q->groupBy('active_window');
//            $q->orderByDesc('id');
        }]);

        $query->orderByDesc('id');

        return $query->paginate($filter['limit'] ?? 10);
    }

    /**
     * Get collection statistic today!
     * @return Builder[]|Collection
     */
    public function getWorkTimeToday(array $filter)
    {
        $query = $this->keyLoggerRepository->query();

        if (isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where(
                'first_time',
                'LIKE',
                Carbon::parse($filter['date_start'])->format('d.m.Y') . '%'
            );
        }

        if (!isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where('first_time', 'LIKE', Carbon::now()->format('d.m.Y') . '%');
        }

        if (isset($filter['date_start']) && isset($filter['date_end'])){
            $query->where('first_time', '>=',  Carbon::parse($filter['date_start'])->format('d.m.Y') . ' 00:00:00');
            $query->where('first_time', '<=',  Carbon::parse($filter['date_end'])->addDays(1)->format('d.m.Y') . ' ' . '23:59:59');
        }

        if(isset($filter['login'])){
            $user = $this->keyLoggerService->getUserById($filter['login']);

            $query->where('login', 'LIKE', '%'.$user->login.'%');
        }

        $query->orderByDesc('id');

        return $query->get();
    }

    /**
     * Calculate work time and return in format "H:i:s"
     * @param Collection $workToday
     * @return string
     */
    public function calculateDateWorkTime(Collection $workToday): string
    {
        $seconds = 0;
        foreach ($workToday as $line) {
            $seconds += Carbon::parse($line->last_active_time)->diffInSeconds(Carbon::parse($line->first_time));
        }
        return gmdate("H:i:s", $seconds);
    }
}
