<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\EmailForEmailNewsletter;
use Laravel\Cashier\Billable;
use App\Models\{Categories, CategoryProducts, Image, StockStatus, Admin\Products\Product};
use App\Models\Order;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use Billable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public const ADMIN_TYPE = 'admin';

    public const CLIENT_TYPE = 'client';

    public function isAdmin()
    {
        return $this->type === self::ADMIN_TYPE;
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function emailReceive()
    {
        return $this->hasOne(EmailForEmailNewsletter::class,'email','email');
    }

    public function image()
    {
        return $this->hasOne(Image::class,'id','image_id');
    }

    public function totalOrders()
    {
        $status = OrderStatuses::where('name', OrderStatuses::CANCEL)->first();
        return $this->hasMany(Order::class,'user_id','id')->where('order_status_id', '!=', $status->id);
    }
}
