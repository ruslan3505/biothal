<?php

namespace App\Console;

use App\Models\Exchange_Rate;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Http;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\ getTable::class,
        Commands\ getTableStockStatus::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            $response = Http::get('https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5');
            Exchange_Rate::where('id', 1)->update(['ccy' => $response[0]['ccy'], 'base_ccy' => $response[0]['base_ccy'], 'buy' => $response[0]['buy'], 'sale' => $response[0]['sale']]);
        })->twiceDaily(8,16);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
