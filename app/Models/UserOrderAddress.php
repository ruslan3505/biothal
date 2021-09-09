<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class UserOrderAddress extends Model
{
    protected $table = 'user_order_address';

    protected $guarded = [];

    public function order() {
        $this->hasOne(Order::class, 'user_order_id', 'id');
    }
}
