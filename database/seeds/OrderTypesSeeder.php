<?php

use Illuminate\Database\Seeder;

class OrderTypesSeeder extends Seeder
{
    public $types = [
        [
            'title' => 'Оплата при получении',
            'type' => 'upon_receipt'
        ],
        [
            'title' => 'Оплата картой',
            'type' => 'card'
        ]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_type')->truncate();

        foreach ($this->types as $type) {
            $PaymentMethod = new \App\Models\OrderType();
            $PaymentMethod->title = $type['title'];
            $PaymentMethod->type = $type['type'];

            $PaymentMethod->save();
        }
    }
}
