<?php

namespace App\Console\Commands;

use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

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
        $url = 'https://www.cbr-xml-daily.ru/daily.xml';
        $res = file_get_contents($url);
        Storage::disk('public')->put('currency.xml', $res);

        return Command::SUCCESS;

//        dd($result);
//        dd( iconv("windows-1251","utf-8",$res));
//        dd('download xml');
//        $currency = Currency::first();
//
//        $client = Http::withoutVerifying()->withHeaders([
//            'Content-Type' => 'application/xhtml+xml',
//            'Accept' => 'application/xml'
//        ]);
//
//        $res = $client
//            ->get('https://www.cbr-xml-daily.ru/daily.xml');
//
//        if ($currency == null) {
//
//            $model = Currency::create([
//                'XmlRates' => iconv("windows-1251","utf-8",$res->body()),
//                'UpdDateTime' => Carbon::now()->format("Y-m-d H:i:s") . ".000",
//            ]);
//
//            dump('create');
//            dump($model);
//            return Command::SUCCESS;
//        }
//
//        $model = $currency->update([
//            'XmlRates' => iconv("windows-1251","utf-8",$res->body()),
//            'UpdDateTime' => Carbon::now()->format("Y-m-d H:i:s"). ".000",
//        ]);
//
//        dump('update');
//        dump($model);
//        return Command::SUCCESS;
    }
}
