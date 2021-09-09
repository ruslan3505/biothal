<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Sales\CheckValues as CheckValuesRequest;
use App\Http\Requests\Products\Sales\Prepare as IdValidRequest;
use App\Http\Requests\Products\Sales\SetSale as SetSaleRequest;
use App\Http\Requests\Products\Sales\Delete as DeleteRequest;
use App\Http\Requests\Products\Sales\Clear as ClearRequest;
use App\Models\Admin\Products\GlobalSales;
use App\Models\Admin\Products\Product;
use App\Models\Admin\Products\Sale;
use Illuminate\Http\Request;
use DataTables;

class SalesController extends Controller
{
    public function getSale(IdValidRequest $request)
    {
        $sale = Sale::find($request->id);

        return response()->json([
            'first_date' => $sale->first_date,
            'last_date' => $sale->last_date,
            'percent' => $sale->percent . '%',
        ]);
    }

    // Присвоить скидку
    public function setSale(SetSaleRequest $request)
    {
        $sale = Sale::find($request->saleId);
        foreach ($request->id as $id) {
            $product = Product::find($id);
            if ($product && $sale) {
                $priceWithSale = round(floatval($product->price - (($sale->percent/100) * $product->price)), 2);
                $product->update([
                    'sale_id' => $sale->id,
                    'price_with_sale' => ceil($priceWithSale),
                ]);
            }
        }

        return response()->json([
            'message' => 'Скидка создана'
        ]);
    }

    // Все Скидки (для таблицы)
    public function getSales()
    {
        $sales = Sale::all();

        return DataTables::of($sales)
            ->addColumn('change', function ($row) {
                return '<button type="button" class="btn btn-warning fas fa-search-dollar" id="b_sale_change_'
                    . $row->id . '" data-id="' . $row->id . '" data-help-sale="tooltip" data-placement="top"
                        title="Редактировать скидку" data-toggle="modal" data-target="#add_sales_modal"></button>';
            })
            ->rawColumns(['first_date', 'last_date', 'change'])
            ->make(true);
    }

    // Добавить Скидку
    public function addSale(CheckValuesRequest $request)
    {
        Sale::create([
            'title' => $request->title,
            'first_date' => $request->first_date,
            'last_date' => $request->last_date,
            'percent' => $request->percent,
        ]);

        $sale = Sale::latest('id')->first();

        return response()->json([
            'message' => 'Скидка успешно создана',
            'id' => $sale->id,
            'name' => $sale->title,
        ]);
    }

    // Добавить Глобальную скидку
    public function addGlobalSale(Request $request)
    {
        GlobalSales::create([
            'sum_modal' => $request->sum_modal,
            'procent_modal' => $request->procent_modal,

        ]);

        $sale = GlobalSales::latest('id')->first();

        return response()->json([
            'message' => 'Скидка успешно создана',
            'id' => $sale->id,
            'sum_modal' => $sale->sum_modal,
            'procent_modal' => $sale->procent_modal,
        ]);
    }



    // Удалить Скидки
    public function deleteSales(DeleteRequest $request)
    {
        $reloadTable = 'notLoad';
        foreach ($request->id as $saleId) {
            $sale = Sale::find($saleId);
            if ($sale) {
                $sales[$saleId] = $sale->title;
                $products = Product::where('sale_id', $saleId)->get();
                if ($reloadTable == 'notLoad') {
                    $reloadTable = count($products) != 0 ? 'reload' : 'notLoad';
                }

                foreach ($products as $product) {
                    $product->update([
                        'sale_id' => null,
                        'price_with_sale' => $product->price,
                    ]);
                }

                $sale->delete();
            }
        }

        return response()->json([
            'message' => 'Таких скидок больше не существует',
            'id' => $sales,
            $reloadTable => true,
        ]);
    }

    // Очистить скидки для продуктов
    public function clearSales(ClearRequest $request)
    {
        $product = Product::find($request->productsId);
        if ($product) {
            $product->update([
                'sale_id' => null,
                'price_with_sale' => null,
            ]);
        }

        return response()->json([
            'message' => 'Скидки успешно удалены'
        ]);
    }

    // Получаем Скидку для изменения
    public function getSalesForChange(IdValidRequest $request)
    {
        $sale = Sale::find($request->id);
        if ($sale) {
            return response()->json([
                'message' => 'success',
                'id' => $sale->id,
                'title' => $sale->title,
                'first_date' => $sale->first_date,
                'last_date' => $sale->last_date,
                'percent' => $sale->percent,
            ]);
        }
        return response()->json([
            'error' => 'id Не найдено!'
        ]);
    }

    // Изменить Скидку
    public function changeSale(CheckValuesRequest $request)
    {
        $sale = Sale::find($request->id);
        if ($sale) {
            $sale->update([
                'title' => $request->title,
                'first_date' => $request->first_date,
                'last_date' => $request->last_date,
                'percent' => $request->percent,
            ]);

            $products = Product::where('sale_id', $request->id)->get();
            foreach ($products as $product) {
                $priceWithSale = round(floatval($product->price - (($sale->percent / 100) * $product->price)), 2);
                $product->update([
                    'price_with_sale' => $priceWithSale,
                ]);
            }
            $reloadTable = count($products) != 0 ? 'reload' : 'notLoad';
        }

        return response()->json([
            'message' => "Скидка успешно изменена",
            $reloadTable => true,
        ]);
    }
}
