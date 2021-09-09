<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Attributes\CheckValues as ValidDataRequest;
use App\Http\Requests\Products\Attributes\Delete as DeleteRequest;
use App\Http\Requests\Products\Attributes\Prepare as PreparationsRequest;
use App\Models\Admin\Products\Product;
use App\Models\Admin\Products\ProductsAttributes;
use Illuminate\Http\Request;
use DataTables;

class AttributesController extends Controller
{
    // Получаем атрибуты для таба "Данные"
    public function getAttributesForProduct(Request $request)
    {
        if (!empty($request->id)){
            $product = Product::where('id', $request->id)->with('productsAttributes')->with('getImage')
                ->get()->toArray();
            $product = !empty($product)
                ? Product::where('id', $request->id)->with('productsAttributes')->with('getImage')
                    ->first()->productsAttributes
                : [];


            return Datatables::of($product)
                ->addColumn('change', function ($row) {
                    return '<button type="button" class="btn btn-warning fas fa-cogs" id="b_attribute_change_'
                        . $row->id . '" data-id="' . $row->id . '" data-help-attribute="tooltip" data-placement="top"
                            title="" data-toggle="modal" data-target="#add_attributes_modal"></button>';
                })
                ->rawColumns(['change'])
                ->make(true);
        }
    }
    // Удалить атрибут
    public function deleteAttributes(DeleteRequest $request)
    {
        foreach ($request->id as $id) {
            $data = ProductsAttributes::find($id);
            if ($data) {
                $data->delete();
            }
        }
        return response()->json([
            'message' => 'Атрибуты успешно удалены'
        ]);
    }

    // Добавить атрибут
    public function addAttributes(ValidDataRequest $request)
    {
        ProductsAttributes::create([
            'name' => $request->title,
            'value' => $request->value,
            'product_id' => $request->id,
        ]);

        return response()->json([
            'message' => 'Атрибут успешно добавлен'
        ]);
    }

    // Получаем атрибут для изменения
    public function getAttributesForChange(PreparationsRequest $request)
    {
        $attribute = ProductsAttributes::find($request->id);

        if ($attribute) {

            return response()->json([
                'message' => 'success',
                'id' => $attribute->id,
                'name' => $attribute->name,
                'value' => $attribute->value,
            ]);
        }

        return response()->json([
            'error' => 'id Не найдено!'
        ]);
    }

    // Изменить Атрибут
    public function changeAttribute(ValidDataRequest $request)
    {
        $data = ProductsAttributes::find($request->id);

        $data->update([
            'name' => $request->title,
            'value' => $request->value,
        ]);

        return response()->json([
            'message' => "Атрибут успешно изменен"
        ]);
    }
}
