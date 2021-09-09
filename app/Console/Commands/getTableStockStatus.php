<?php

namespace App\Console\Commands;

use App\Models\Admin\Products\Product;
use App\Models\StockStatus;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class getTableStockStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getTableStockStatus';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'getTableStockStatus';

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
        $graph = DB::connection('mysql2')->select("SELECT * FROM oc_stock_status");
        $first_names = array_column($graph, 'name');
        dump($first_names);
//        for($i = 0; $i <= count($graph); $i++){
        foreach ($graph as $key=>$value){
            [
                $value->stock_status_id,
                $value->name,
            ];
            StockStatus::insert([
                ['name' => $value->name, 'stock_status_id' => $value->stock_status_id]
            ]);
            }
//        }
    }
}
