<?php

namespace App\Models\Admin\Accessories;

use App\Models\Admin\Products\Product;
use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Accessories extends Model
{
    protected $table = 'accessories';

    protected $guarded = [];

    public function Accessory()
    {
        return $this->hasOne(Accessories::class,'id','parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'accessory_products','accessory_id','product_id');
    }

    public function Category()
    {
        return $this->hasOne(Categories::class,'id','parent_id')->OrderBy('ordering', 'ASC');
    }

    use HasSlug;

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

}
