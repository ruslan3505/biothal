<?php

use Illuminate\Database\Seeder;

class OrderStatusesSeeder extends Seeder
{
    protected $names = [
        'active',
        'payment_process',
        'shipping_process',
        'finish',
        'pre_order',
        'paid',
        'cancel',
        'unfinished',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->truncate();

        foreach ($this->names as $name) {
            $orderStatus = new \App\Models\OrderStatuses();
            $orderStatus->name = $name;

            $orderStatus->save();
        }
    }
}
