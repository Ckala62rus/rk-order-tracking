<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class WorkStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:WorkStart';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Procedure from WorkStart';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            $workStart = DB::connection('sqlsrv')->statement('SET NOCOUNT OFF EXEC [CustomersOrders].[dbo].[RunWorkStart]');
            if ($workStart) {
                $workEnd = DB::connection('sqlsrv')->statement('SET NOCOUNT OFF EXEC [CustomersOrders].[dbo].[RunWorkEnd]');
                if ($workEnd) {
                    DB::connection('sqlsrv')->statement('SET NOCOUNT OFF EXEC [CustomersOrders].[dbo].[RunRdpResult]');
                }
            }
        } catch (Exception $exception) {
            Log::info($exception->getMessage());
        }
    }
}
