<?php

namespace App\Models\Admin\Products;

use App\Models\Categories;
use Illuminate\Database\Eloquent\Model;
use App\Models\Admin\Products\{
    InformationAttributes,
    InformationToLayout
};

class Information extends Model
{
    protected $table = 'information';
    protected $primaryKey = 'information_id';
    protected $guarded = [];

    public function attributes()
    {
        return $this->hasOne(InformationAttributes::class, 'information_id', 'information_id');
    }

    public function layout()
    {
        return $this->hasOne(InformationToLayout::class, 'information_id', 'information_id');
    }
}
