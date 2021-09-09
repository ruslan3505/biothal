<?php

namespace App\Models\Admin\Products;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class InformationAttributes extends Model
{
    protected $table = 'information_attributes';
    protected $primaryKey = 'information_id';
    protected $guarded = [];

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
