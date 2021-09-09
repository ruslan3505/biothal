<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\Add as ProductAddRequest;
use App\Http\Requests\Products\GetForChange as IdValidationRequest;
use App\Http\Requests\Products\Change as ProductChangeRequest;
use App\Models\AccessoryProducts;
use App\Models\Admin\Accessories\Accessories;
use App\Models\Admin\Products\Product;
use App\Models\Admin\Products\Sale;
use App\Models\Categories;
use App\Models\CategoryProducts;
use App\Models\Image;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Storage;

class ProductsController extends Controller
{
    public function index(Request $request)
    {
        $products = Product::all();

        if ($request->ajax()) {
            foreach ($products as $key => $val){
                $products[$key]['number'] = $key+1;
            }

          return Datatables::of($products)
              ->editColumn('name', function ($row) {
                  return '<a href="/admin/products/show/product?link=' . $row->link . '">' . $row->name . '</a>';
              })
                ->editColumn('image_id', function ($row) {
                    $img = '/storage/img/products/' . $row->getImage->name;

                    return '<a href="' . $img . '" class="thumbnail"><img src="' . $img . '" class="rounded-circle"
                                             width="100" height="100" alt="Изображение товара"></a>';
                })
                ->editColumn('sale_id', function ($row) {
                    return $row->sale_id != null ?
                        $row->getSale->title.'<br/>'.$row->getSale->percent.'%' :
                        'Скидка отсутствует';

                })
              ->editColumn('price', function ($row) {
                  if ($row->getSale) {
                      return '<span style="text-decoration: line-through;">' . $row->price . 'грн.</span><br>
                          <div class="text-danger">' . $row->price_with_sale . ' грн.</div>';
                  }
                  return $row->price;
              })
                ->addColumn('action', function ($row) {
                    return '<button type="button" class="btn btn-outline-dark fa fa-wrench" id="b_product_change_'
                        . $row->id . '" data-toggle="modal" data-target="#add_product" data-id="' . $row->id . '"></button>';
                })
                ->rawColumns(['action', 'description', 'image_id', 'composition', 'sale_id', 'price', 'name'])
                ->make(true);
        }

        $imagesAll = Image::all();
        $sales = Sale::all();

        // Если нет изображенией, выводим подсказку
        if (count($imagesAll) == 0) {
            $imagesAll = null;
        }
        $categories = Categories::all();
        $accessories = Accessories::all();

        return view('admin.products.index', [
            'images' => $imagesAll,
            'products' => $products,
            'sales' => $sales,
            'categories' => $categories,
            'accessories' => $accessories,
        ]);
    }

    // Получаем продукт для заполнения модалки
    public function getProductForChange(IdValidationRequest $request)
    {
        $product = Product::find($request->id);

        if ($product != null) {

            return response()->json([
                'message' => 'success',
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'meta_description' => $product->meta_description,
                'meta_keywords' => $product->meta_keywords,
                'link' => $product->link,
                'image_id' => $product->image_id,
                'composition' => $product->composition,
                'price' => $product->price,
            ]);
        }

        return response()->json([
            'error' => 'id Не найдено!'
        ]);
    }

    // Добавить продукт
    public function addProduct(ProductAddRequest $request)
    {
        Product::create([
            'name' => $request->name,
            'description' => $request->description,
            'meta_description' => $request->meta_description,
            'link' => $request->link,
            'meta_keywords' => $request->meta_keywords,
            'image_id' => $request->image_radio,
            'composition' => $request->composition,
            'price' => $request->price,

        ]);
        $id_products = Product::max('id');
        CategoryProducts::insert([
            'category_id' => $request->categories,
            'product_id' => $id_products
        ]);
        AccessoryProducts::insert([
            'accessory_id' => $request->accessories,
            'product_id' => $id_products
        ]);

        return response()->json([
            'message' => 'Продукт успешно создан'
        ]);
    }

    // Удалить продукты
    public function deleteProducts(Request $request)
    {
        if(!empty($request->id)){
            foreach ($request->id as $id) {
                $product = Product::find($id);
                if ($product) {
                    $datas = $product->productsAttributes;
                    if (count($datas) != 0) {
                        foreach ($datas as $data) {
                            $data->delete();
                        }
                    }
                    $product->delete();
                }
                CategoryProducts::where('product_id', '=', $id )->delete();
            }
            return response()->json([
                'message' => 'Продукты успешно удалены'
            ]);
        }else{
            return response()->json([
                'message' => 'Выберите продукт'
            ]);
        }

    }

    // Изменить продукт
    public function changeProduct(ProductChangeRequest $request)
    {
        $product = Product::find($request->product_id);

        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'meta_description' => $request->meta_description,
            'link' => $request->link,
            'meta_keywords' => $request->meta_keywords,
            'image_id' => $request->image_radio,
            'composition' => $request->composition,
            'price' => $request->price,
//            'price_with_sale' => $request->price_with_sale,

        ]);

        CategoryProducts::where('product_id', '=', $request->product_id)->delete();

        CategoryProducts::insert([
            'category_id' => $request->categories,
            'product_id' => $request->product_id
        ]);
        AccessoryProducts::where('product_id', '=', $request->product_id)->delete();
        AccessoryProducts::insert([
            'accessory_id' => $request->accessories,
            'product_id' => $request->product_id
        ]);

        return response()->json([
            'message' => "Продукт успешно изменен"
        ]);
    }
}
