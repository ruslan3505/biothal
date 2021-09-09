<?php

namespace App\Console\Commands;

use App\Models\Admin\Products\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class getTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'getTable';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'My getTable';

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
        $graph = DB::connection('mysql2')->select("SELECT * FROM oc_product");

        foreach ($graph as $key=>$value){
            [
                 $value->model,
                 $value->sku,
                 $value->upc,
                 $value->price,
                 $value->quantity,
//                 $value->stock_status_id,
                 $value->image,
                 $value->shipping,
                 $value->status,
                 $value->mpn,
                 $value->ean,
                 $value->sort_order,
                 $value->date_added,
                 $value->date_modified,
            ];

            Product::insert([
                ['name' => $value->model, 'description' => $value->sku, 'meta_description' => $value->upc, 'ean' => $value->ean,
                 'price_with_sale' => null, 'meta_keywords' => '', 'price' => $value->price, 'quantity' => $value->quantity, 'image_link' => 0, 'link' => '',
                 'status' => $value->status, 'sort_order' => $value->sort_order, 'minimum' => $value->minimum,'composition' => '', 'image_id' => 195,
                 'stock_status_id' => $value->stock_status_id, 'created_at' => $value->date_added, 'updated_at' => $value->date_modified]
            ]);
        }
    }
}
