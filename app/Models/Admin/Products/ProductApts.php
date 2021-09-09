<?php

namespace App\Models\Admin\Products;

use App\Models\Image;
use Illuminate\Database\Eloquent\Model;

class ProductApts extends Model
{
    protected $table = 'product_apts';

    protected $fillable = [
        'product_id', 'tab_title', 'tab_desc', 'sort_order'
    ];

    public $timestamps = FALSE;
}
