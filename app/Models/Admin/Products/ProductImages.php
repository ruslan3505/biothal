<?php

namespace App\Models\Admin\Products;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $table = 'product_images';

    protected $fillable = [
        'product_id', 'image', 'sort_order'
    ];

    public function images()
    {
        return $this->hasOne(Image::class,'id','image');
    }

    public $timestamps = FALSE;
}
