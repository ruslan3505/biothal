<?php

namespace App\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;

class ProductDescription extends Model
{
    protected $table = "product_description";
    protected $guarded = [];
    public $timestamps = false;
    public $incrementing = false;
}
