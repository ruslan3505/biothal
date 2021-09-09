<?php

namespace App\Models;

use App\Models\Order;
use Illuminate\Database\Eloquent\Model;

class OrderStatuses extends Model
{
    public const PENDING = 'pending';

    public const ACTIVE = 'active';
    public const PAYMENT_PROCESS = 'payment_process';
    public const SHIPPING_PROCESS = 'shipping_process';
    public const FINISH = 'finish';
    public const PRE_ORDER = 'pre_order';
    public const PAID = 'paid';
    public const CANCEL = 'cancel';
    public const UNFINISHED = 'unfinished';

    public const STATUS = [
        OrderStatuses::ACTIVE => 'Закупка',
        OrderStatuses::PAYMENT_PROCESS => 'Оплата',
        OrderStatuses::SHIPPING_PROCESS => 'Доставка',
        OrderStatuses::FINISH => 'Доставлено',
        OrderStatuses::PRE_ORDER => 'Предзаказ',
        OrderStatuses::PAID => 'Оплачен',
        OrderStatuses::CANCEL => 'Отменен',
        OrderStatuses::UNFINISHED => 'Не закончен',
    ];

    protected $table = "order_statuses";

    protected $guarded = [];

    public function order()
    {
        return $this->hasOne(Order::class, 'order_status_id', 'id');
    }
}
