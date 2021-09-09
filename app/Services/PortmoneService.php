<?php


namespace App\Services;


use App\Models\Payment;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Jenssegers\Agent\Agent;

class PortmoneService
{
    public const GETAWAY_URL = 'https://www.portmone.com.ua/gateway/';

    public function makeRequest($amount, $order)
    {
        $agent = new Agent();
//        Log::info($agent->platform());
//        Log::info($agent->browser());

        $payment = new Payment();
        $payment->order_id = $order->id;
        $payment->amount = $order->total_sum;
        $payment->status = Payment::STATUS_PENDING;
        $payment->save();

        $url = self::GETAWAY_URL . '?' . http_build_query([
                'payee_id' => env('PORTMONE_SHOP_ID'),
                'shop_order_number' => $order->id,
                'bill_amount' => $payment->amount,
                'description' => env('PORTMONE_DESCRIPTION'),
                'success_url' => route('portmone.success', ['order_id' => $order->id]),
                'failure_url' => route('portmone.cancel', ['order_id' => $order->id]),
                'lang' => 'ru',
                'encoding' => 'UTF-8',
                'exp_time' => env('PORTMONE_WAITING_TIME_FOR_PAYMENT') * 60,
                'type' => 'light',
            ]);

        return $url;
    }
}
