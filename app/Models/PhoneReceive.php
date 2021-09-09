<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneReceive extends Model
{
    protected $table = 'phone_for_receive';

    protected $guarded = [];

    public function group()
    {
        return $this->hasOne(PhoneGroup::class, 'id', 'group_id');
    }
}
