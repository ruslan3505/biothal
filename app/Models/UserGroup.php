<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class UserGroup extends Model
{
    protected $table = 'user_group';

    protected $guarded = [];

    public function attachedUser()
    {
        return $this->hasOne(User::class,'id','attached_user_id')->with('totalOrders');
    }

    public function mainUser()
    {
        return $this->hasOne(User::class,'id','user_id')->with('totalOrders');
    }
}
