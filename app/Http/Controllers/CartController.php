<?php

namespace App\Http\Controllers;

use App\Http\Requests\Cart\ValidFormModalCheckRequest;
use App\Models\Admin\Products\GlobalSales;
use App\Models\UserOrderAddress;
use GuzzleHttp\Client;
use App\Http\Requests\Cart\ValidCartRequest;
use App\Http\Requests\Cart\ValidFormCheckoutRequest;
use App\Models\Cart_Product;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use App\Traits\GlobalTrait;
use Illuminate\Support\Str;

class CartController extends Controller
{
    use GlobalTrait;

    public function insInCartHome(Request $request)
    {

        $cart = ShoppingCart::where([
        ['uuid', '=', session('uuid')],
        ['user_id', '=', Auth::id()],
        ])->first();

        if (empty($cart)) {
            $cart = ShoppingCart::create([
                'uuid' => session('uuid'),
                'user_id' => Auth::id(),
            ]);
        }
        $cartProduct = Cart_Product::where([
            ['cart_id', '=', $cart->id],
            ['product_id', '=', $request->input('product_id')],
        ])->first();

        if (empty($cartProduct)) {
            $cartProduct = Cart_Product::create(['cart_id' => $cart->id, 'product_id' => $request->input('product_id'), 'count' => $request->input('count')]);
        } else {
            $cartProduct = Cart_Product::where([['cart_id', $cart->id], ['product_id', '=', $request->input('product_id')]])->update([
                'count' => ($cartProduct['count'] + $request->input('count'))
            ]);
        }

        $data = $this->global_traits();
        $countAll = $data['countAll'];
        $uuid = $data['uuid'];
        $product_price = $data['product_price'];
        $cart_join = $data['cart_join'];
        $product_sale = $data['product_sale'];
        $cart_prod_count = $data['cart_prod_count'];
        $sum = $data['sum'];
        $sum_sale = $data['sum_sale'];
        $sumAll = $data['sumAll'];
        $region = $data['region'];
        $count_sale_product = $data['count_sale_product'];

        $html = View::make('partials.partialsBasket',   compact('countAll', 'uuid', 'product_price', 'cart_join', 'product_sale', 'cart_prod_count', 'sum', 'sum_sale', 'sumAll', 'region', 'count_sale_product'))->render();
        $html_for_checkout = View::make('partials.checkout',   compact('countAll', 'uuid', 'product_price', 'cart_join', 'product_sale', 'cart_prod_count', 'sum', 'sum_sale', 'sumAll', 'region', 'count_sale_product'))->render();
        return response()->json(['html' => $html, 'html_for_checkout' => $html_for_checkout, 'countAll' => $countAll, 'success' => 1]);
    }

    public function insInCart(ValidCartRequest $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->first();

        if (empty($cart)) {
            $cart = ShoppingCart::create([
                'uuid' => session('uuid'),
                'user_id' => Auth::id(),
            ]);
        }

        $cartProduct = Cart_Product::where([
            ['cart_id', '=', $cart->id],
            ['product_id', '=', $request->input('product_id')],
        ])->first();

        if (empty($cartProduct)) {
            $cartProduct = Cart_Product::create(['cart_id' => $cart->id, 'product_id' => $request->input('product_id'), 'count' => $request->input('count')]);
        } else {
            $cartProduct = Cart_Product::where([['cart_id', '=', $cart->id], ['product_id', '=', $request->input('product_id')]])->update([
                'count' => ($cartProduct['count'] + $request->input('count'))
            ]);
        }
        return response()->json(['success' => 1]);
    }

    public function delCart(Request $request)
    {
        Cart_Product::where('product_id', '=', $request->input('product_id'))->delete();
        $data = $this->global_traits();
        $countAll = $data['countAll'];
        $uuid = $data['uuid'];
        $product_price = $data['product_price'];
        $cart_join = $data['cart_join'];
        $product_sale = $data['product_sale'];
        $cart_prod_count = $data['cart_prod_count'];
        $sum = $data['sum'];
        $sum_sale = $data['sum_sale'];
        $sumAll = $data['sumAll'];
        $region = $data['region'];
        $count_sale_product = $data['count_sale_product'];

        $html = View::make('partials.partialsBasket',   compact('countAll', 'uuid', 'product_price', 'cart_join', 'product_sale', 'cart_prod_count', 'sum', 'sum_sale', 'sumAll', 'region', 'count_sale_product'))->render();
        $html_for_checkout = View::make('partials.checkout',   compact('countAll', 'uuid', 'product_price', 'cart_join', 'product_sale', 'cart_prod_count', 'sum', 'sum_sale', 'sumAll', 'region', 'count_sale_product'))->render();
        return response()->json(['html' => $html, 'html_for_checkout' => $html_for_checkout, 'countAll' => $countAll, 'success' => 1]);
    }

    public function setCheck(Request $request)
    {
        return view('checkout');
    }

    public function checkout(Request $request)
    {
        $cities = $request->input('cities');
        $region = $request->input('region');
        $http = new Client();
        $resp = $http->request('POST', 'https://api.novaposhta.ua/v2.0/json/', [
            'json' => [
                'apiKey' => '3290bef07476a0a0d06726d54cec7d34',
                'modelName' => 'AddressGeneral',
                'calledMethod' => 'getWarehouses',
                'methodProperties' => [

                    "RegionsDescription" => $region,
                    "Language" => "ru",
                    "Limit" => 20,
                    "CityName" => $cities,

                ],
            ]
        ]);

        return response()->json(['data' => json_decode($resp->getBody()->getContents(), true)]);
    }

    public function check(ValidFormCheckoutRequest $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->first();

        ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->update(['order_type_id' => $request->input('order_type')]);

        $UserOrderAddress = UserOrderAddress::where([
            ['phone', '=', $request->input('phone')],
            ['name', '=', $request->input('name')],
            ['LastName', '=', $request->input('LastName')],
            ['region', '=', $request->input('region')],
            ['cities', '=', $request->input('cities')],
            ['department', '=', $request->input('department')],
          ])->first();

        if (empty($UserOrderAddress)) {
            $user_order_address = UserOrderAddress::create([
                'phone' => $request->input('phone'),
                'name' => $request->input('name'),
                'LastName' => $request->input('LastName'),
                'region' => $request->input('region'),
                'cities' => $request->input('cities'),
                'department' => $request->input('department'),
                'not_call' => $request->input('not_call'),
                'shopping_id' => $cart->id,
            ]);
        } else {
            Cart_Product::where([['cart_id', '=', $UserOrderAddress->shopping_id]])->update(['cart_id' => $cart->id]);
            $user_order_address = UserOrderAddress::where([
                ['phone', '=', $request->input('phone')],
                ['name', '=', $request->input('name')],
                ['LastName', '=', $request->input('LastName')],
                ['region', '=', $request->input('region')],
                ['cities', '=', $request->input('cities')],
                ['department', '=', $request->input('department')],
            ])->update(['shopping_id' => $cart->id, 'not_call' => $request->input('not_call')]);
        }

        ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->update(['uuid' => Str::uuid()]);

        return response()->json(['success' => 1]);
    }

    public function checkModalOneClick(ValidFormModalCheckRequest $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->first();

        if (!empty($cart)) {
            ShoppingCart::where([
                ['uuid', '=', session('uuid')],
                ['user_id', '=', Auth::id()],
            ])->update(['order_type_id' => $request->input('order_type')]);
        } else {
//            dd(session('uuid'));
            $cart = ShoppingCart::create([
                'uuid' => session('uuid'),
                'user_id' => Auth::id(),
                'order_type_id' => $request->input('order_type')
            ]);
        }
//        dd(123);
        $UserOrderAddress = UserOrderAddress::where([
            ['phone', '=', $request->input('phone')],
            ['name', '=', $request->input('name')],
        ])->first();

        if (empty($UserOrderAddress)) {
            UserOrderAddress::create([
                'phone' => $request->input('phone'),
                'name' => $request->input('name'),
                'shopping_id' => $cart->id,
            ]);
        } else {
            Cart_Product::where([['cart_id', '=', $UserOrderAddress->shopping_id]])->update(['cart_id' => $cart->id]);
            UserOrderAddress::where([
                ['phone', '=', $request->input('phone')],
                ['name', '=', $request->input('name')],
            ])->update(['shopping_id' => $cart->id]);
        }

//        ShoppingCart::where([
//            ['uuid', '=', session('uuid')],
//            ['user_id', '=', Auth::id()],
//        ])->update(['uuid' => Str::uuid()]);

        return response()->json(['success' => 1]);
    }

    public function plus_count(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->first();

        if ($cart){
            Cart_Product::where([
                ['product_id', '=', $request->input('product_id')],
            ])->update(['count' => $request->input('count')]);
        }

        $data = $this->global_traits();
        $countAll = $data['countAll'];
        $sumAll = $data['sumAll'];
        $procent_modal = $data['procent_modal'];
        $sumAll_not_sale = $data['sumAll_not_sale'];

        return response()->json(['countAll' => $countAll, 'sumAll' => $sumAll, 'sumAll_not_sale' => $sumAll_not_sale, 'procent_modal' => $procent_modal, 'success' => 1]);

    }

    public function minus_count(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->first();

        if ($cart){
            Cart_Product::where([
                ['product_id', '=', $request->input('product_id')],
            ])->update(['count' => $request->input('count')]);
        }

        $data = $this->global_traits();
        $countAll = $data['countAll'];
        $sumAll = $data['sumAll'];
        $procent_modal = $data['procent_modal'];
        $sumAll_not_sale = $data['sumAll_not_sale'];

        return response()->json(['countAll' => $countAll, 'sumAll' => $sumAll, 'sumAll_not_sale' => $sumAll_not_sale, 'procent_modal' => $procent_modal, 'success' => 1]);

    }
}
