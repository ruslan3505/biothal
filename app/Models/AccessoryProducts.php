<?php

namespace App\Models;

use App\Models\Admin\Accessories\Accessories;
use Illuminate\Database\Eloquent\Model;

class AccessoryProducts extends Model
{
    protected $table = 'accessory_products';

    protected $guarded = [];

    public function accessoryDetails() {
        return $this->hasMany(Accessories::class,'id','accessory_id')->orderBy('ordering', 'asc');
    }
}
