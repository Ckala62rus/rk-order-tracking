<?php

namespace App\Services;

use App\Models\LoginKeyLogger;
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
     * @param LoginKeyLoggerService $keyLoggerService
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
        $query = $this->getQueryForStatistic($filter, true);
        return $query->paginate($filter['limit']);
    }

    /**
     * Get all statistic with aggregate row.
     * @param array $filter
     * @return Builder
     */
    public function getAllStatisticWithAggregation(array $filter): Builder
    {
        return $this->getQueryForStatistic($filter, false);
    }

    /**
     * Return statistic collection
     * @param array $filter
     * @return Collection
     */
    public function getQueryForExportExcel(array $filter): Collection
    {
        $query = $this->getQueryForStatistic($filter, true);
        return $query->get();
    }

    /**
     * Return Builder for query statistic
     * @param array $filter
     * @param bool $loadRelations
     * @return Builder
     */
    public function getQueryForStatistic(array $filter, bool $loadRelations = false): Builder
    {
        $query = $this->keyLoggerRepository->query();

        if (isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where('first_time', '>=', Carbon::parse($filter['date_start'])->format('Y-m-d') . 'T00:00:00');
            $query->where('first_time', '<=', Carbon::parse($filter['date_start'])->format('Y-m-d') . 'T23:59:59');
        }

        if (isset($filter['date_start']) && isset($filter['date_end'])){
            $query->where(function ($query) use ($filter){
                    $start = Carbon::parse($filter['date_start'])->format('Y-m-d');
                    $end = Carbon::parse($filter['date_end'])->format('Y-m-d');

                $query->whereRaw(DB::raw("first_time between convert(datetime, '" . $start . "T00:00:00', 126) and convert(datetime, '" . $end . "T23:59:59', 126)"));
            });
        }

        if (!isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where('first_time', '>=', Carbon::now()->format('Y-m-d') . 'T00:00:00');
            $query->where('first_time', '<=', Carbon::now()->format('Y-m-d') . 'T23:59:59');
        }

        if(isset($filter['login'])){
            $user = $this->keyLoggerService->getUserById($filter['login']);
            $query->where('login', 'LIKE', '%'.$user->login.'%');
        }

        if ($loadRelations) {
            $query->with(['details' => function($q){
                $q->select(
                    DB::raw('max(session_id) as session_id'),
                    DB::raw('active_window'),
                    DB::raw('max(date) as date'),
                );
                $q->groupBy('active_window');
            }]);

            $query->with(['activeWindowsSeconds' => function($q){
                $q->select(
                    DB::raw('session_id'),
                    DB::raw('window'),
                    DB::raw('SUM(seconds) as seconds'),
                );
                $q->groupBy('session_id', 'window');
                $q->orderByDesc('seconds');
            }]);
        }

        $query->orderByDesc('id');
        return $query;
    }

    /**
     * Get collection statistic today!
     * @return Builder[]|Collection
     */
    public function getWorkTimeToday(array $filter)
    {
        $query = $this->keyLoggerRepository->query();

        if (isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where('first_time', '>=', Carbon::parse($filter['date_start'])->format('Y-m-d') . 'T00:00:00');
            $query->where('first_time', '<=', Carbon::parse($filter['date_start'])->format('Y-m-d') . 'T23:59:59');
        }

        if (!isset($filter['date_start']) && !isset($filter['date_end'])){
            $query->where('first_time', '>=', Carbon::now()->format('Y-m-d') . 'T00:00:00');
            $query->where('first_time', '<=', Carbon::now()->format('Y-m-d') . 'T23:59:59');
        }

        if (isset($filter['date_start']) && isset($filter['date_end'])){
            $query->where(function ($query) use ($filter){
                $start = Carbon::parse($filter['date_start'])->format('Y-m-d');
                $end = Carbon::parse($filter['date_end'])->format('Y-m-d');
                $query->whereRaw(DB::raw("first_time between convert(datetime, '" . $start . "T00:00:00', 126) and convert(datetime, '" . $end . "T23:59:59', 126)"));
            });
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

    /**
     * Get fio from LoginKeyLogger table by domain
     * @param string $login
     * @return mixed
     */
    public function getModel(string $login)
    {
        $explodeLogin = explode('\\', $login);

        $model =  LoginKeyLogger::where('login', 'LIKE', $explodeLogin[1])->first();
        if (!$model) {
            return null;
        }

        return $model;
    }
}
