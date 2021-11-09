<?php

namespace App\Console\Commands;

use App\Models\WorkResult;
use App\Repositories\StatisticRdpRepository;
use App\Repositories\UserRdpRepository;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateStatisticsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:UpdateRDPStatistics';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get and write to database RDP statistics';

    /**
     * @var UserRdpRepository
     */
    private UserRdpRepository $userRdpRepository;

    /**
     * @var StatisticRdpRepository
     */
    private StatisticRdpRepository $statisticRdpRepository;

    /**
     * UpdateStatisticsCommand constructor.
     * @param UserRdpRepository $userRdpRepository
     * @param StatisticRdpRepository $statisticRdpRepository
     */
    public function __construct(
        UserRdpRepository $userRdpRepository,
        StatisticRdpRepository $statisticRdpRepository
    ) {
        parent::__construct();
        $this->userRdpRepository = $userRdpRepository;
        $this->statisticRdpRepository = $statisticRdpRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // обновляем список пользователей в бд
        $this->getUsersAndSaveDatabase();

        // получаем обновленный список юзеров
        $users = $this
            ->userRdpRepository
            ->all();

        //механизм удаления статистики и её пересбора
        $query = $this
            ->statisticRdpRepository
            ->query();

        $s = $this
            ->statisticRdpRepository
            ->whereDate($query, config('statistic_rdp.last_date'));

        $s->delete();

        $date = Carbon::createFromFormat('Y-m-d H:i:s', config('statistic_rdp.last_date') . ' 00:00:00');
        $now = Carbon::now();

        // разница в днях от statistic_rdp.last_date до сегодняшней
        $diffDays = $date->diffInDays($now);

        // инициализация прогресс бара
        $bar = $this->output->createProgressBar(count($users));

        // старт прогресс бара
        $bar->start();

        for ($i = 1; $i <= $diffDays; $i++){

            foreach ($users as $user) {
                $statistic = WorkResult::where('Property_run', $user->login)
                    ->where('Date_run', $date->format('Y-m-d'))
                    ->get();

                if ($statistic->isEmpty()){
                    continue;
                }

                $result = [
                    'date' => '',
                    'login' => '',
                    'work_time' => 0,
                ];

//                dd($statistic);

                foreach ($statistic as $row){
                    $result['date'] = $date->format('Y-m-d');
                    $result['login'] = $user->login;
                    $result['work_time'] += $row->WorkMinute;
                }

                // сохраняем статистику по пользователю за конкретную дату
                $this->statisticRdpRepository->store($result);
            }
            $date->addDay();

            // для итерации прогресс бара
            $bar->advance();
        }

        // завершение прогресс бара
        $bar->finish();
    }

    /**
     * Get unique users login and save in database
     */
    public function getUsersAndSaveDatabase()
    {
        $users = DB::connection('winapi')
            ->table('EventRDPs')
            ->select('Property')
            ->distinct()
            ->get();

        foreach ($users as $user){

            $query = $this
                ->userRdpRepository
                ->query();

            $query = $this
                ->userRdpRepository
                ->whereLogin($query, $user->Property);

            $ifExistUser = $query->first();

            if (!$ifExistUser){
                $this
                    ->userRdpRepository
                    ->store(['login' => $user->Property]);
            }
        }
    }
}
