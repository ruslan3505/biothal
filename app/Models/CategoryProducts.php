<?php

namespace App\Models;

use App\Models\Admin\Products\Product;
use Illuminate\Database\Eloquent\Model;

class CategoryProducts extends Model
{
    protected $table = 'category_products';

    protected $guarded = [];

    public function Category()
    {
        return $this->hasOne(Categories::class,'id','parent_id');
    }

    public function categoryName()
    {
        return $this->hasOne(Categories::class,'id','category_id');
    }

//    public function products()
//    {
//        return $this->belongsToMany(Product::class,'category_products','category_id','product_id');
//    }

}


