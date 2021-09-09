<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderType extends Model
{
    protected $table = 'order_type';

    protected $guarded = [];

    public const CARD_METHOD = 'card';

    public function shopping_cart()
    {
        return $this->hasMany(ShoppingCart::class,'id');
    }
}
