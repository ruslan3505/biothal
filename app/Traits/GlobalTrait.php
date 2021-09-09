<?php
namespace App\Traits;

use App\Models\Admin\Products\GlobalSales;
use App\Models\Admin\Products\Product;
use App\Models\Cart_Product;
use App\Models\Region;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Str;

trait GlobalTrait
{
    public function global_traits() {

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

        if (session('uuid') == null) {
            $uuid = (string) Str::uuid();
            session(['uuid' => $uuid]);
        } else {
            $uuid = session('uuid');
        }

        $product_price = Product::with('getImage')->where('sale_id', '!=', null)->take(2)->get();
        $product_sale = Product::with('getImage')->where('sale_id', '=', null)->take(2)->get();

        $cart_prod_count = [];
        $countAll = 0;

        $shopping_card = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', Auth::id()],
        ])->first();

        $cart_join = [];
        if (!empty($shopping_card)) {
            $cart_prod_count = Cart_Product::where([['cart_id', '=', $shopping_card->id]])->get();
            foreach ($cart_prod_count as $key => $value) {
                $countAll += $value['count'];
            }
            $cart_join = DB::table('cart_products')
                ->leftJoin('products', 'cart_products.product_id', '=', 'products.id')
                ->where([['cart_id', '=', $shopping_card->id]])
                ->get();
        }

        $imagesPath = public_path("storage/img/carousel/"); // путь к папке с глобальными картинками
        $files = []; // массив файлов
        foreach (glob($imagesPath . "*.{jpg,png,gif,jpeg}", GLOB_BRACE) as $filename) { // ищет все картинки через glob
            $files[] = $filename;
        }

        $global_sale = GlobalSales::latest('id')->first();
        $sum_modal = $global_sale['sum_modal'];
        $procent_modal = $global_sale['procent_modal'];

        $sum = 0;
        $sum_sale = 0;
        $sumAll = 0;
        $sumAll_not_sale = 0;

        foreach ($cart_join as $cart)
        {
            if ((($cart->price_with_sale) == null))
            {
                $sum += ($cart->price * $cart->count);
            }
            else {
                $sum_sale += ($cart->price_with_sale * $cart->count);
            }
            $sumAll = ($sum + $sum_sale);
            $sumAll_not_sale = $sumAll;
        }

        if ($sumAll >= $sum_modal ){
            $sumAll = $sumAll - (($sumAll * $procent_modal)/ 100);
        }


        $region = Region::select('region', 'id')->get()->toArray();
        $region = array_merge([['region' => 'Выберите область']], $region);
        $count_sale_product = Product::with('getImage')->where('sale_id', '!=', null)->count();

        return [
            'countAll' => $countAll,
            'uuid' => $uuid,
            'product_price' => $product_price,
            'cart_join' => $cart_join,
            'product_sale' => $product_sale,
            'cart_prod_count' => $cart_prod_count,
            'sum' => $sum,
            'sum_sale' => $sum_sale,
            'sumAll_not_sale' => $sumAll_not_sale,
            'sumAll' => $sumAll,
            'region' => $region,
            'count_sale_product' => $count_sale_product,
            'procent_modal' => $procent_modal,
            ];
    }
}
