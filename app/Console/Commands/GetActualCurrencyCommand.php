<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetActualCurrencyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:GetActualCurrency';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get actual currency from CRB Russia';

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
        $url = config('windows_service.api_url_base') . 'currency';
        $client = Http::withoutVerifying()->withHeaders([
            'Content-Type' => 'application/xhtml+xml',
            'Accept' => 'application/xml',
        ]);

        $res = $client
            ->get($url);
        $res->json();

        return Command::SUCCESS;
    }
}
