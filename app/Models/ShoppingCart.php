<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShoppingCart extends Model
{
    protected $table = 'shopping_cart';

    protected $fillable = [
        'uuid', 'user_id', 'order_type_id', 'order_import', 'total'
    ];

    public const ACTIVE = 'active';
    public const PAYMENT_PROCESS = 'payment_process';
    public const SHIPPING_PROCESS = 'shipping_process';
    public const FINISH = 'finish';

    public const STATUS = [
        ShoppingCart::ACTIVE => 'Закупка',
        ShoppingCart::PAYMENT_PROCESS => 'Оплата',
        ShoppingCart::SHIPPING_PROCESS => 'Доставка',
        ShoppingCart::FINISH => 'Доставлено',
    ];

    public function products()
    {
        return $this->belongsToMany('App\Models\Admin\Products\Product','cart_products','cart_id','product_id')
            ->withPivot('count');
    }

    public function userOrderAddress()
    {
        return $this->hasOne("App\Models\UserOrderAddress", 'shopping_id', 'id');
    }

    public function orderTypes()
    {
        return $this->belongsTo(OrderType::class,'order_type_id');
    }
}
