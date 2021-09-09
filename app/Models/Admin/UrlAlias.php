<?php

namespace App\models\Admin;

use Illuminate\Database\Eloquent\Model;

class UrlAlias extends Model
{
    protected $table = 'url_alias';
    protected $primaryKey = 'url_alias_id';
    protected $guarded = [];
}
