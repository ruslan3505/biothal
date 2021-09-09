<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Products\Product;

class OrderProduct extends Model
{
    protected $table = 'order_products';

    public function attr () {
        return $this->hasOne(Product::class, 'id', 'product_id')->with([
            'image',
            'productDescription',
            'getSale'
        ]);
    }

    public function productData () {
        return $this->hasOne(Product::class, 'id', 'product_id')->with('image', 'productDescription');
    }
}
