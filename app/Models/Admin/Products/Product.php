<?php

namespace App\Models\Admin\Products;

use App\Models\AccessoryProducts;
use App\Models\Admin\Accessories\Accessories;
use App\Models\Categories;
use App\Models\CategoryProducts;
use App\Models\Exchange_Rate;
use App\Models\Image;
use App\Models\Admin\Products\ProductDescription;
use App\Models\StockStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Product extends Model
{
    protected $table = 'products';
    protected $primaryKey = 'id';

    protected $guarded = [];

    protected $appends = [
        'currency',
        'category'
    ];

    public function productsAttributes()
    {
        return $this->hasMany(ProductsAttributes::class,'product_id', 'id');
    }

    public function image()
    {
        return $this->belongsTo(Image::class);
    }

    public function productImages()
    {
        return $this->hasMany(ProductImages::class, 'product_id', 'id')->orderBy('sort_order', 'asc')->with('images');
    }

    public function productApts()
    {
        return $this->hasMany(ProductApts::class,'product_id', 'id')->orderBy('sort_order', 'asc');
    }

    public function getSale()
    {
        return $this->hasOne(Sale::class,'id','sale_id');
    }

    public function productDescription () {
        return $this->hasOne(ProductDescription::class,'product_id','id');
    }

    public function productTo1C () {
        return $this->hasOne(ProductTo1C::class, 'product_id', 'id');
    }

    public function stockStatus () {
        return $this->hasOne(StockStatus::class, 'stock_status_id', 'stock_status_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Categories::class,'category_products','product_id','category_id');
    }

    public function productCategory()
    {
        return $this->hasOne(CategoryProducts::class, 'product_id', 'id');
    }

    public function accessories()
    {
        return $this->hasMany(AccessoryProducts::class,'product_id','id')->with('accessoryDetails');
    }

    public function getCurrencyAttribute()
    {
        $rates = Exchange_Rate::where('id', 1)->first();
        $exchange = $rates->buy;

        return !isset($this->price_with_sale) ? round($this->price / $exchange) : round($this->price_with_sale / $exchange);
    }

    public function getCategoryAttribute()
    {
        $categoryProducts = CategoryProducts::where('product_id', $this->id)->first();
        $category = Categories::where('id', $categoryProducts->category_id)->first();
        return $category->slug;
    }
}
