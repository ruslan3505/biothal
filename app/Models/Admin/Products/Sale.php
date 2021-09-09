<?php

namespace App\Models\Admin\Products;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'first_date', 'last_date', 'title', 'percent'
    ];

    public $timestamps = FALSE;
}
