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
        return $this->getQueryForAggregateStatistic($filter, true);
    }

    /**
     * Get all user activities as array
     * @param Collection $data
     * @return array
     */
    public function getWindowActivitiesForAggregateStatistic(Collection $data): array
    {
        $allActivities = [];
        foreach ($data as $activities) {
            foreach ($activities->activeWindowsSeconds as $activity) {
                $allActivities[] = $activity->toArray();
            }
        }

        return $allActivities;
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
//        $query->dd();
        return $query;
    }

    /**
     * Return Builder for query statistic
     * @param array $filter
     * @param bool $loadRelations
     * @return Builder
     */
    public function getQueryForAggregateStatistic(array $filter, bool $loadRelations = false): Builder
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

//        $query->groupBy('login');
        $query->orderByDesc('id');
//        $query->dd();
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
     * calculate work time for aggregate statistic
     * @param array $workToday
     * @return false|string
     */
    public function calculateWorkTime(array $workToday){
        $seconds = 0;
        foreach ($workToday as $line) {
            $seconds += Carbon::parse($line->last_active_time)->diffInSeconds(Carbon::parse($line->first_time));
        }
        return gmdate("H:i:s", $seconds);
    }

    /**
     * Sort worktime for each user
     * @param array $data
     * @return array
     */
    public function calculateWorkTimeEachUser(array $data): array
    {
        $out = [];

        foreach ($data as $login => $activities) {
            $out[mb_strtolower($login)] = $this->calculateWorkTime($activities);
        }

//        dd($out);
        return $out;
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

    /**
     * Sort user activities by login then date.
     * example [
     *     "user_login" => [
     *         "2025-01-01" => [
     *              [activity],
     *              [activity],
     *              ...
     *         ]
     *     ]
     * ]
     * @param array $activities
     * @return array
     */
    public function aggregationStatistic(array $activities): array
    {
        $groupActivities = [];

        foreach ($activities as $activity) {
            // если нет таколо ключа(логина), то добавляем модель в $groupActive
            $dateCreateEntity = Carbon::parse($activity["first_time"])->format("Y-m-d");
            $groupActivities[$activity["login"]][$dateCreateEntity][] = $activity;
        }

        return $groupActivities;
    }

    /**
     * Sort aggregation activities by id for each activity for each user.
     * @param array $groupActivities
     * @return array
     */
    public function sortAggregationStatisticById(array $groupActivities): array
    {
        $groupActivitiesSorted = [];

        foreach ($groupActivities as $login => $data) {
            foreach ($data as $year => $groupActivities) {
                // сортируем активности конкретного пользователя по id
                $sortedGroupActivity = collect($groupActivities)->sortByDesc('id');

                if ($sortedGroupActivity) {
                    $groupActivitiesSorted[$login][$year] = $sortedGroupActivity->values()->all();
                }

            }
        }

        return $groupActivitiesSorted;
    }

    /**
     * Retrieve first and last activity for get first and last time.
     * @param array $groupActivitiesSorted
     * @return array
     */
    public function retrieveFirstAndLastActivityElements(array $groupActivitiesSorted): array
    {
        $statistic = [];
        $userLogins = [];

        // извлекаем первый и последний элементы и заносим в новый массив
        foreach ($groupActivitiesSorted as $login => $date) {

            $userModel = $this->getModel($login);
            if ($userModel) {
                $statistic[$login] = $userModel;
            }

//            foreach ($date as $groupActivity) {
//                $data = collect($groupActivity);
//
//                if ($data->isNotEmpty()) {
//                    $statistic[$login][] = $this
//                        ->prepareAggregationStatistic($data, $userLogins);
//                }
//            }
        }
        return $statistic;
    }

    /**
     * Group Activities for each user
     * @param Collection $data
     * @return array
     */
    public function sortActivitiesByLogin(Collection $data): array
    {
        $out = [];
        foreach ($data as $activity) {
            $out[$activity->login][] = $activity;
        }

        return $out;
    }

    /**
     * Prepare statistic array
     * @param \Illuminate\Support\Collection $data
     * @param array $userLogins
     * @return \Illuminate\Support\Collection
     */
//    protected function prepareAggregationStatistic(\Illuminate\Support\Collection $data, array $userLogins): array
    protected function prepareAggregationStatistic(\Illuminate\Support\Collection $data, array $userLogins): \Illuminate\Support\Collection
    {
        return $data->pluck('id');

//        $first = $data->last();
//        $last = $data->first();

//        if (!array_key_exists($first["login"], $userLogins)) {
//            $userModel = $this->getModel($first["login"]);
//            $userLogins[$first["login"]] = $userModel ?? null;
//        }

//        return [
//            "id" => $first["id"],
//            "login" => $first["login"],
//            "fio" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->fio : "Отсутствует в 1С",
//            "first_time" => $first["first_time"],
//            "last_active_time" => $last["last_active_time"],
//            "department" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->department : "Отсутствует в 1С",
//            "organization" => $userLogins[$first["login"]] ? $userLogins[$first["login"]]->organization : "Отсутствует в 1С",
//        ];
    }
}
