<?php

namespace App\Http\Controllers;

use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Session;
use Stripe;

class StripePaymentController extends Controller
{
    public function stripe()
    {
        ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', auth()->id()],
            ['status', '=', ShoppingCart::ACTIVE]
        ])->update(['status' => ShoppingCart::PAYMENT_PROCESS]);

        return view('stripe');
    }

    /**
     * success response method
     *
     * @return \Illuminate\Http\Response
     */
    public function stripePost(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', auth()->id()],
            ['status', '=', ShoppingCart::PAYMENT_PROCESS],
        ])->first();

        if ($cart) {
            $fullPrice = 0;

            foreach ($cart->products as $item) {
                $count = $item->pivot->count;
                $price = $item->price;

                $fullPrice += round(floatval($count * $price), 2);
            }

            Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            Stripe\Charge::create([
                "amount" => 100 * $fullPrice,
                "currency" => "uah",
                "source" => $request->stripeToken,
                "description" => "Test payment from itsolutionstuff.com."
            ]);

            Session::flash('success', 'Payment successful!');

            return back();
        } else {
            Session::flash('wrong', 'Payment was wrong!');

            return back();
        }
    }
}
