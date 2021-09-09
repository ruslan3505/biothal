<?php

namespace App\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;

class ProductsAttributes extends Model
{
    protected $table = 'products_attributes';

    protected $fillable = [
        'name', 'value', 'product_id'
    ];

    public $timestamps = FALSE;
}
