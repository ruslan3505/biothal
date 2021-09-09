<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderHistory;
use App\Models\OrderStatuses;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PortmoneController extends Controller
{
    public function index()
    {
        return view('portmone');
    }


    public function success($order_id)
    {
        $payment = Payment::where('order_id', $order_id)
            ->first();

        $payment->status = Payment::STATUS_SUCCESS;
        $payment->save();

        $status = OrderStatuses::where('name', OrderStatuses::PAID)->first();

        $order = Order::where('id', $order_id)->first();
        $order->order_status_id = $status->id;
        $order->save();

        OrderHistory::create([
            'order_id' => $order_id,
            'notify' => 0,
            'comment' => 'Оплачен',
            'status_id' => $status->id,
        ]);

        Log::debug('Success order from portmone: '.$order_id);
        return redirect(env('FRONT_APP_URL'). '/order-status/'. $order->token . '?frame=true');
    }

    public function cancel($order_id)
    {
        $payment = Payment::where('order_id', $order_id)
            ->first();

        $payment->status = Payment::STATUS_CANCELED;
        $payment->save();

        $status = OrderStatuses::where('name', OrderStatuses::CANCEL)->first();

        $order = Order::where('id', $order_id)->first();
        $order->order_status_id = $status->id;
        $order->save();

        OrderHistory::create([
            'order_id' => $order_id,
            'notify' => 0,
            'comment' => 'Отмена оплаты',
            'status_id' => $status->id,
        ]);
        Log::debug('Cancel order from portmone: '.$order_id);
        return redirect(env('FRONT_APP_URL'). '/order-cancel/'. $order->token . '?frame=true');
    }
}
