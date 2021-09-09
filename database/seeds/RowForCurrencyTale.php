<?php

use Illuminate\Database\Seeder;
use App\Models\Exchange_Rate;

class RowForCurrencyTale extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Exchange_Rate::create(['ccy' => 'USD', 'base_ccy' => 'UAH', 'buy' => '26.45000', 'sale' => '26.85000']);
    }
}
