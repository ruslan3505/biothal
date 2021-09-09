<?php

use Illuminate\Database\Seeder;
use App\Models\Admin\Settings;

class RowForSettingsTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Settings::create(['setting_name' => 'BlackLine', 'setting_content' => 'Бесплатная доставка при заказе от 1500грн']);
    }
}
