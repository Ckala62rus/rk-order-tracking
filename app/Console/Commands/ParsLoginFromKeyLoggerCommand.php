<?php

namespace App\Console\Commands;

use App\Models\LoginKeyLogger;
use App\Services\KeyLoggerService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ParsLoginFromKeyLoggerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:keyloggerlogin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update key login logger from 1C';

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
        $users = Http::withBasicAuth(
            config('1c.1c.login'),
            config('1c.1c.password')
        )
            ->get(config('1c.1c.url'))
            ->json();

        if ($users){
            foreach ($users as $user) {
                if (strlen($user['Email']) > 0) {
                    $login = explode('@', $user['Email'])[0];

                    LoginKeyLogger::updateOrCreate(
                        ['login' => $login],
                        ['login' => $login, 'fio' => $user['FullName']]
                    );
                }
            }
        }
    }
}
