<?php

namespace App\Models;

use App\Models\Admin\Products\GlobalSales;
use App\Models\OrderHistory;
use App\Models\ShoppingCart;
use App\Models\OrderStatuses;
use App\Models\OrderProduct;
use App\Models\UserOrderAddress;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = "order";

    public const GLOBAL_SALES = 1;
    public const GROUP_SALES = 2;

    protected $guarded = [];

    public function userAddress()
    {
        return $this->belongsTo(UserOrderAddress::class, 'user_order_id');
    }

    public function productHistory () {
        return $this->hasMany(OrderHistory::class, 'order_id', 'id');
    }

    public function orderStatus () {
        return $this->belongsTo(OrderStatuses::class, 'order_status_id');
    }

    public function orderType () {
        return $this->hasOne(OrderType::class, 'id', 'order_type_id');
    }

    public function shoppingCart()
    {
        return $this->hasOneThrough(
            ShoppingCart::class,
            UserOrderAddress::class,
            'id', // Foreign key on the cars table...
            'id', // Foreign key on the owners table...
            'user_order_id', // Local key on the mechanics table...
            'shopping_id' // Local key on the cars table...
        );
    }

    public function products () {
        return $this->hasMany(OrderProduct::class, 'order_id', 'id')->with('attr');
    }

    public function user () {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function payment () {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }

    public function globalSales () {
        return $this->hasOne(GlobalSales::class, 'id', 'sale_id');
    }

    public function groupSales () {
        return $this->hasOne(GroupSale::class, 'id', 'sale_id');
    }
}
