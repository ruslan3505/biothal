<?php

namespace App\Models;

use App\Models\Admin\Accessories\Accessories;
use App\Models\Admin\Products\Information;
use App\Models\Admin\Products\InformationAttributes;
use App\Models\Admin\Products\InformationToLayout;
use Illuminate\Support\Arr;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Admin\Products\Product;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    protected $table = 'categories';

    protected $guarded = [];

    public function Category()
    {
        return $this->hasOne(Categories::class,'id','parent_id')->OrderBy('ordering', 'ASC');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class,'category_products','category_id','product_id');
    }

    public function children()
    {
        return $this->hasMany(Categories::class,'parent_id','id')->with('Category');
    }

    public function childrenArticle()
    {
        return $this->hasMany(InformationToLayout::class,'layout_id','id')->with(['info','attribute']);
    }

    public function Accessory()
    {
        return $this->hasMany(Accessories::class,'parent_id','id');
    }

    public function childrenArticleBottom()
    {
        return $this->hasMany(InformationToLayout::class,'layout_id','id')->with('infoForBottom');
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


