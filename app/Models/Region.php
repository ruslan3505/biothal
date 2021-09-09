<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'region';

    protected $fillable = [
        'id', 'region'
    ];

    public function userRegions () {
        $this->hasMany('App\Models\UserOrderAddress');
    }
}
