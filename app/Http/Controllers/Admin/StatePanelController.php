<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShoppingCart;
use Illuminate\Http\Request;
use DataTables;

class StatePanelController extends Controller
{
    public function index(Request $request)
    {
        $statuses = ShoppingCart::STATUS;

        if ($request->ajax()) {
            $cart = ShoppingCart::all();
            if (!empty($cart)) {
                return Datatables::of($cart)
                    ->editColumn('status', function ($row) {
                        if ($row->status == ShoppingCart::ACTIVE)
                            return 'Закупка';
                        if ($row->status == ShoppingCart::PAYMENT_PROCESS)
                            return 'Оплата';
                        if ($row->status == ShoppingCart::SHIPPING_PROCESS)
                            return 'Доставка';
                        if ($row->status == ShoppingCart::FINISH)
                            return 'Доставленно';
                    })
                    ->addColumn('fullPrice', function ($row) {
                        $fullPrice = 0;

                        foreach ($row->products as $item) {
                            $count = $item->pivot->count;
                            $price = $item->price_with_sale;

                            $fullPrice = round(floatval($fullPrice + ($count * $price)), 2);
                        }
                        return $fullPrice;
                    })
                    ->addColumn('action', function ($row) {
                        return '<button type="button" class="btn btn-outline-dark fa fa-wrench"
                     id="b_shopping_cart_' . $row->id . '" data-id="' . $row->id . '"></button>';
                    })
                    ->addColumn('products', function ($row) {
                        return '<button type="button" class="btn btn-info fas fa-boxes"
                    id="b_shopping_products_' . $row->id . '" data-id="' . $row->id . '"></button>';
                    })
                    ->rawColumns(['action', 'products'])
                    ->make(true);
            }
        }

        return view('admin.statePanel.index', [
            'statuses' => $statuses,
        ]);
    }

    public function getProducts(Request $request)
    {
        if ($request->ajax()) {
            $cart = ShoppingCart::find($request->cartId);
            if (!empty($cart)) {
                return Datatables::of($cart->products)
                    ->addColumn('image', function ($row) {
                        $img = '/img/' . $row->getImage->name;

                        return '<a href="' . $img . '" class="thumbnail">
                                        <img src="' . $img . '" class="rounded-circle"
                                             width="100" height="100" alt="Изображение товара">
                                    </a>';
                    })
                    ->addColumn('name', function ($row) {
                        return '<a href="/admin/products/show/product?link=' . $row->link . '">' . $row->name . '</a>';
                    })
                    ->addColumn('count', function ($row) {
                        return $row->pivot->count;
                    })
                    ->addColumn('sale', function ($row) {
                        return $row->sale_id != null ? $row->getSale->percent . '%' : 'скидка отсутствует';
                    })
                    ->addColumn('price', function ($row) {
                        return $row->price;
                    })
                    ->addColumn('p*c', function ($row) {
                        $oldPrice = round(floatval($row->price * $row->pivot->count), 2);

                        if ($row->getSale) {
                            $newPrice = round(floatval($row->price_with_sale * $row->pivot->count), 2);

                            return '<span style="text-decoration: line-through;">' . $oldPrice . 'грн.</span><br>
                          <div class="text-danger">' . $newPrice . ' грн.</div>';
                        }

                        return $oldPrice;
                    })
                    ->rawColumns(['image', 'name', 'p*c'])
                    ->make(true);
            }
            return response()->json([
                'message' => 'Заглушка, удалить если будет только ajax',
            ]);
        }

    }

    public function getShoppingCart(Request $request)
    {
        $cart = ShoppingCart::find($request->cartId);
        $fullPrice = 0;

        foreach ($cart->products as $item) {
            $count = $item->pivot->count;
            $price = $item->price_with_sale;

            $fullPrice += round(floatval($count * $price), 2);
        }

        return response()->json([
            'status' => $cart->status,
            'date' => $cart->created_at,
            'fullPrice' => $fullPrice,
        ]);
    }
}
