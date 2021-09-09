<?php

namespace App\Models;

use App\Models\Admin\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Cart_Product extends Model
{
    protected $table = 'cart_products';

    public $timestamps = false;

    protected $fillable = [
        'cart_id', 'product_id', 'count'
    ];
}
