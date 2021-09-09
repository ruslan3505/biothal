<?php

namespace App\Http\Controllers;

use App\Http\Requests\Products\Add;
use App\Models\Admin\Products\Product;
use App\Models\Admin\Products\Sale;
use App\Models\Categories;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TestController extends Controller
{
    public function index(Request $request)
    {
        $data = Categories::all();

        if ($request->ajax()) {
            return response()->json([
                'true' => 'true',
            ]);
        }

        return view('admin.Test', ['categories' => $data]);
    }

    public function getProducts()
    {
        return Product::query()->with('getImage');
    }

    public function getCategories()
    {
        return Categories::all();
    }

    public function getProduct($id)
    {
        return Product::with('getImage')->find($id);
    }

    public function getProductAttributes($productId)
    {
        return Product::with('getImage')->find($productId)->productsAttributes;
    }

    // Тестовые кнопки
    public function home(Request $request)      // Тестовый метод \\
    {
        if (!session('cartId')) {
            echo 'true';
        } else {
            echo 'false';
        }

        $cart2 = ShoppingCart::where([
            ['uuid', '=', $request->uuid],
            ['user_id', '=', auth()->id()],
        ])->first();

        session()->has('cartId');

        if (session('cartId') == null) {
            session()->put('cartId', $cart2->id);
            echo 'true';
        } else {
            echo 'false';
        }

        return response()->json([
            'message' => 'successful'
        ]);
    }

    public function getMailing(Request $request)        // Тестовый метод
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', $request->uuid],
            ['user_id', '=', auth()->id()],
            ['status', '=', 'active'],
        ])->first();

        $product = $cart->products()->find(2);

        if ($product) {
            echo 'true';
        } else {
            echo 'false';
        }
    }

    // В разработке
    public function changeCount(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', auth()->id()],
        ])->last();

        $cart->products()->updateExistingPivot($request->product_id, ['count' => $request->count]);
        $fullPrice = 0;

        foreach ($cart->products as $item) {
            $count = $item->pivot->count;
            $price = $item->price_with_sale;

            $fullPrice += round(floatval($count * $price), 2);
        }

        $cart->update([
            'status' => ShoppingCart::ACTIVE,
        ]);

        return response()->json([
            'message' => 'success',
            'fullPrice' => $fullPrice,
        ]);
    }

    public function deleteProductFromShoppingCart(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', auth()->id()],
        ])->first();

        if ($cart) {
            $product = $cart->products()->find($request->product_id);
            if ($product) {
                $fullPrice = 0;
                foreach ($cart->products as $item) {
                    $count = $item->pivot->count;
                    $price = $item->price_with_sale;
                    $fullPrice += round(floatval($count * $price), 2);
                }

                $cart->update([
                    'status' => ShoppingCart::ACTIVE,
                ]);

                return response()->json([
                    'message' => 'продукт успешно удален.',
                    'fullPrice' => $fullPrice
                ]);
            }

            return response()->json([
                'error' => 'Продукт не найден!'
            ]);
        }

        return response()->json([
            'error' => 'Корзина не найдена!'
        ]);
    }

    public function addToShoppingCart(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', session('uuid')],
            ['user_id', '=', auth()->id()],
        ])->first();

        if ($cart == null) {
            ShoppingCart::create([
                'uuid' => session('uuid'),
                'user_id' => auth()->id(),
            ]);
        }

        $product = $cart->products()->find($request->product_id);

        if ($product == null) {
            $cart->products()->attach($request->product_id);

            $fullPrice = 0;

            foreach ($cart->products as $item) {
                $count = $item->pivot->count;
                $price = $item->price_with_sale;
                $fullPrice += round(floatval($count * $price), 2);
            }

            $cart->update([
                'status' => ShoppingCart::ACTIVE,
            ]);

            return response()->json([
                'message' => 'Продукт успешно добавлен в корзину',
                'fullPrice' => $fullPrice,
            ]);
        }

        return response()->json([
            'error' => 'Продукт уже добавлен'
        ]);
    }

    public function getShoppingCart(Request $request)
    {
        $cart = ShoppingCart::where([
            ['uuid', '=', $request->uuid],
            ['user_id', '=', auth()->id()],
        ])->first();

        return response()->json([
            'cart' => $cart,
            'products' => $cart->products
        ]);

//        $product = $cart->products->each(function ($item, $key){
//            echo $item->pivot->count;
//        });
    }
    // Готовые методы
    public function findProductsByCategory($categoryId)
    {
        $category = Categories::find($categoryId);
        $products = $category->products;
        return $products;
    }

    public function categoryNeeds($categoryId)
    {
        $categoryNeeds = Categories::where('parent_id', $categoryId)->orderBy('ordering', 'asc')->get();
        return $categoryNeeds;
    }

    public function paginatiOn()
    {
        $products = Product::with('getImage')->paginate(5);
        return view('home', ['products' => $products]);
    }

    public function sortByBig($value)
    {
        $products = Product::with('getImage')->orderBy($value, 'desc')->get();
        return $products;
    }

    public function sortByCost($min, $max, $sort)
    {
        $products = Product::with('getImage')->where([
            ['price', '>', $min],
            ['price', '<', $max]
        ])
            ->orderBy('price', $sort)
            ->get();
        return $products;
    }

    public function sortByLow($value)
    {
        $products = Product::with('getImage')->orderBy($value, 'asc')->get();
        return $products;
    }

    public function sortWhere($column, $value)
    {
        $products = Product::with('getImage')->where($column, $value)->get();
        return $products;
    }
}
