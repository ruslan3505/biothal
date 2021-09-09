<?php

namespace App\Models\Admin;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    protected $table = 'settings';
    protected $guarded = [];
    public $timestamps = false;
}
