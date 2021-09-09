<?php

use Illuminate\Database\Seeder;

class OrderDeliveryTypeSeeder extends Seeder
{
    public $types = [
        [
            'title' => 'Отделение Новой Почты'
        ],
        [
            'title' => 'Адресная доставка'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_delivery_type')->truncate();

        foreach ($this->types as $type) {
            $DeliveryMethod = new \App\Models\OrderDeliveryType();
            $DeliveryMethod->title = $type['title'];

            $DeliveryMethod->save();
        }
    }
}
