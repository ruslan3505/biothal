<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Requests\Products\{
    Create as ProductCreate,
    Update as ProductUpdate
};

use App\Http\Requests\Products\Information\{
    Create as InformationCreateRequest,
    Update as InformationUpdateRequest
};

use App\Http\Requests\Products\Sales\Discount\{
    Create as DiscountCreateRequest,
    Update as DiscountUpdateRequest
};

use App\Http\Requests\Products\Sales\DiscountGlobal\{
    Create as DiscountGlobalCreateRequest,
    Update as DiscountGlobalUpdateRequest
};

use App\Http\Requests\Products\Sales\DiscountGroup\{
    Create as DiscountGroupCreateRequest,
    Update as DiscountGroupUpdateRequest
};

use Illuminate\Support\Facades\Log;
use App\Models\Admin\Products\{
    GlobalSales,
    ProductApts,
    ProductTo1C,
    ProductImages,
    ProductsAttributes,
    ProductDescription,
    Information,
    InformationAttributes,
    InformationToLayout,
    Sale,
};

use App\Models\Admin\UrlAlias;
use App\Models\GroupSale;

use App\Models\{AccessoryProducts,
    Admin\Accessories\Accessories,
    Categories,
    CategoryProducts,
    Image,
    StockStatus,
    Admin\Products\Product};

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DataTables;

class NewProductsController extends Controller
{
    public function indexNew(Request $request)
    {
        $stock_statuses = StockStatus::all();
        $products = Product::with([
            'productDescription',
            'image'
            ]);
        if (!empty($request->all())) {
            if ($request->input('status') !== null) {
                $products = $products->where('status', $request->input('status'));
            }
            if ($request->input('availability') !== null) {
                $products = $products->where('stock_status_id', $request->input('availability'));
                Log::info($products->get());
            }
            if (!empty($request->input('title_product'))) {
                $title = $request->input('title_product');
                $products = $products->whereHas('productDescription', function ($query) use ($title) {
                    $query->where('name', 'like', '%' . $title . '%');
                });
            }
        }
        $products = $products->get()->toArray();
        $sales = Sale::where('active', 1)->get();
        return view('admin.products.indexNew', compact('products', 'stock_statuses', 'sales'));
    }

    public function createProd()
    {
        $stock_statuses = StockStatus::all();
        $categories = Categories::orderBy('parent_id')->where('type_category', 0)->get()->toArray();

        foreach ($categories as $category_key => $category) {
            $main_cat_name = Categories::where('id', $category['parent_id'])->value('title');
            $full_cat_path = empty($main_cat_name) ? $category['title'] : $main_cat_name . " > " . $category['title'];
            $categories[$category_key]["full_name"] = $full_cat_path;
        }

        return view('admin.products.changeNewProd', compact(
            'stock_statuses',
            'categories'
        ));
    }

    public function createProdProcess(ProductCreate $request)
    {
        unset($request['image_gallary_input']);
        /* START: updating data for product*/
        $product = Product::create(array_filter($request->all(), function ($element, $key) {
            return !is_array($element) && $key != '_token';
        }, ARRAY_FILTER_USE_BOTH));

        /* END: updating data for product*/

        /* START: updating main data for product*/
        foreach ($request['product_description'] as $product_description) {
            ProductDescription::updateOrInsert(
                [
                    'product_id' => $product['id'],
                    'language_id' => $product_description['language_id']
                ],
                $product_description
            );
        }
        if (isset($request['product_image'])) {
            ProductImages::where('product_id', $product['id'])->delete();
            foreach ($request['product_image'] as $productImage) {
                if (!empty($productImage['image'])) {
                    ProductImages::create([
                        'product_id' => $product['id'],
                        'image' => $productImage['image'],
                        'sort_order' => $productImage['sort_order'] ?? 0
                    ]);
                }
            }
        }
        ProductDescription::where('product_id', $product['id'])->whereNotIn('language_id', array_keys($request['product_description']))->delete();
        /* END: updating main data for product*/

        /* START: updating categories data for product*/
        foreach ($request['categoryProducts'] as $product_category) {
            CategoryProducts::insert(
                [
                    'product_id' => $product['id'],
                    'category_id' => $product_category
                ]
            );
        }
        /* START: updating relations data for product*/
        if (!empty($request['productTo1C']['1c_id'])) {
            $product->productTo1C()->updateOrInsert(
                ['product_id' => $product['id']],
                ['1c_id' => $request['productTo1C']['1c_id']]
            );
        }
        /* END: updating relations data for product*/

        /* START: updating images data for product*/

        /* END: updating images data for product*/

        /* START: updating apts data for product*/
        ProductApts::where('product_id', $product['id'])->delete();
        if (!empty($request['product_apt'])) {
            foreach ($request['product_apt'] as $product_apt) {
                ProductApts::insert(array_merge(['product_id' => $product['id']], $product_apt));
            }
        }
        /* END: updating apts data for product*/

        /* START: adding accessory data for product*/
        if (isset($request['accessoryProducts'])) {
            AccessoryProducts::where('product_id', $product['id'])->delete();
            foreach ($request['accessoryProducts'] as $productAccessory) {
                if (!empty($productAccessory['accessory_id'])) {
                    AccessoryProducts::insert([
                        'product_id' => $product['id'],
                        'accessory_id' => $productAccessory['accessory_id']
                    ]);
                }
            }
        }
        /* END: adding accessory data for product*/

        return redirect()->route('admin.products.updateProductNew', ['id' => $product['id']])
            ->with('success', 'Товар был успешно создан!');
    }

    public function getProd($id)
    {
        $product = Product::find($id);
        $images = Image::paginate(15);
        if (empty($product)) {
            abort(404);
        }

        $stock_statuses = StockStatus::all()->toArray();
        $categories = Categories::orderBy('parent_id')->where('type_category', 0)->get()->toArray();
        foreach ($categories as $category_key => $category) {
            $main_cat_name = Categories::where('id', $category['parent_id'])->value('title');
            $full_cat_path = empty($main_cat_name) ? $category['title'] : $main_cat_name . " > " . $category['title'];
            $categories[$category_key]["full_name"] = $full_cat_path;
        }

        $category = Categories::find($product->productCategory->category_id);

        $accessories = Accessories::where('parent_id', $category->parent_id ? $category->parent_id : $product->productCategory->category_id)->get()->toArray();

        return view('admin.products.changeNewProd', compact(
            'id',
            'product',
            'stock_statuses',
            'accessories',
            'categories',
            'images'
        ));
    }


    public function updateProduct(ProductUpdate $request, $id)
    {
        unset($request['image_gallary_input']);

        $product = Product::find($id);
        if (empty($product)) {
            abort(404);
        }
        /* START: updating main data for product*/
        foreach ($request['product_description'] as $product_description) {
            ProductDescription::updateOrInsert(
                [
                    'product_id' => $id,
                    'language_id' => $product_description['language_id']
                ],
                $product_description
            );
        }

        ProductDescription::where('product_id', $id)->whereNotIn('language_id', array_keys($request['product_description']))->delete();
        /* END: updating main data for product*/
        if (isset($request['product_image'])) {
            ProductImages::where('product_id', $product['id'])->delete();
            foreach ($request['product_image'] as $productImage) {
                if (!empty($productImage['image'])) {
                    ProductImages::create([
                        'product_id' => $product['id'],
                        'image' => $productImage['image'],
                        'sort_order' => $productImage['sort_order'] ?? 0
                    ]);
                }
            }
        } else {
            ProductImages::where('product_id', $product['id'])->delete();
        }
        /* START: updating data for product*/
        $product->update(array_filter($request->all(), function ($element, $key) {
            return !is_array($element) && $key != '_token';
        }, ARRAY_FILTER_USE_BOTH));
        /* END: updating data for product*/

        /* START: updating relations data for product*/
        if (!empty($request['productTo1C']['1c_id'])) {
            $product->productTo1C()->updateOrInsert(
                ['product_id' => $id],
                ['1c_id' => $request['productTo1C']['1c_id']]
            );
        }
        /* END: updating relations data for product*/

        /* START: updating categories data for product*/
        foreach ($request['categoryProducts'] as $product_category) {
            CategoryProducts::where('product_id', '=', $product['id'])->delete();

            CategoryProducts::insert(
                [
                    'product_id' => $product['id'],
                    'category_id' => $product_category
                ]
            );
        }
        /* END: updating categories data for product*/

        /* START: updating apts data for product*/
        ProductApts::where('product_id', $id)->delete();
        if (!empty($request['product_apt'])) {
            foreach ($request['product_apt'] as $product_apt) {
                ProductApts::insert(array_merge(['product_id' => $id], $product_apt));
            }
        }
        /* END: updating apts data for product*/

        /* START: updating accessory data for product*/
        if (isset($request['accessoryProducts'])) {
            AccessoryProducts::where('product_id', $product['id'])->delete();
            foreach ($request['accessoryProducts'] as $productAccessory) {
                if (!empty($productAccessory['accessory_id'])) {
                    AccessoryProducts::insert([
                        'product_id' => $product['id'],
                        'accessory_id' => $productAccessory['accessory_id']
                    ]);
                }
            }
        } else {
            AccessoryProducts::where('product_id', $product['id'])->delete();
        }
        /* END: updating accessory data for product*/

        return back()->with('success', 'Товар был успешно обновлен!');
    }

    public function information(Request $request)
    {
        $articles = Information::paginate(10);

        if ($request->has('name')) {
            $articles->sortByAsc('attributes.title');
        } else if ($request->has('sort')) {
            $articles->orderBy('sort_order', 'asc');
        }

        return view('admin.products.information', compact(
            'articles'
        ));
    }

    public function changeInformation($id)
    {
        $article = Information::with('attributes')->where('information_id', $id)->first();

        $layouts = Categories::where('type_category', 1)->get(); // Получение всех информационных категорий

        $url = UrlAlias::where([
            'type' => 'information',
            'query' => $id
        ])->first();

        return view('admin.products.changeInformation', compact(
            'layouts',
            'article',
            'url'
        ));
    }

    public function createInformation()
    {
        $layouts = Categories::where('type_category', 1)->get(); // Получение всех информационных категорий

        return view('admin.products.createInformation', compact(
            'layouts'
        ));
    }

    public function updateInformation(InformationUpdateRequest $request, $id)
    {
        $article = Information::where('information_id', $id)->first();

        if (empty($article)) {
            abort(404);
        }

        $article->bottom = isset($request->bottom) ? true : false;
        $article->sort_order = $request->sort_order;
        $article->status = $request->status;
        $article->save();

        $articleAttributes = InformationAttributes::where('information_id', $id)->first();
        $articleAttributes->title = $request->title;
        $articleAttributes->description = $request->description;
        $articleAttributes->meta_title = $request->meta_title;
        $articleAttributes->meta_description = $request->meta_description;
        $articleAttributes->meta_keywords = $request->meta_keywords;
        $articleAttributes->save();

        $articleLayout = InformationToLayout::where('information_id', $id)->first();
        $articleLayout->layout_id = $request->information_layout;
        $articleLayout->save();

        if (isset($request->keyword)) {
            $url = UrlAlias::where([
                'type' => 'information',
                'query' => $id
            ])->first();
            if (!empty($url)) {
                $url->keyword = $request->keyword;
                $url->save();
            } else {
                UrlAlias::create([
                    'type' => 'information',
                    'query' => $id,
                    'keyword' => $request->keyword
                ]);
            }
        }

        return redirect()->back()->with('success', 'Вы успешно обновили статью');
    }

    public function saveInformation(InformationCreateRequest $request)
    {
        $article = Information::create([
            'bottom' => isset($request->bottom) ? true : false,
            'sort_order' => $request->sort_order,
            'status' => $request->status
        ]);

        InformationAttributes::create([
            'information_id' => $article->information_id,
            'title' => $request->title,
            'description' => $request->description,
            'meta_title' => $request->meta_title,
            'meta_description' => $request->meta_description,
            'meta_keywords' => $request->meta_keywords
        ]);

        InformationToLayout::create([
            'information_id' => $article->information_id,
            'store_id' => 0,
            'layout_id' => $request->information_layout
        ]);

        if (isset($request->keyword)) {
            UrlAlias::create([
                'type' => 'information',
                'query' => $article->information_id,
                'keyword' => $request->keyword
            ]);
        }

        return redirect('admin/products/changeInformation/' . $article->information_id)->with('success', 'Вы успешно создали статью');
    }

    public function deleteInformation(Request $request)
    {
        $ids = json_decode($request->input('ids'));
        foreach ($ids as $information) {
            Information::where('information_id', $information)->delete();
            InformationAttributes::where('information_id', $information)->delete();
            InformationToLayout::where('information_id', $information)->delete();
            UrlAlias::where([
                'type' => 'information',
                'query' => $information
            ])->delete();

        }
        return response()->json(['success' => 1]);
    }

    public function deleteProd(Request $request)
    {
        $ids = json_decode($request->input('ids'));

        foreach ($ids as $product) {
            Product::where('id', $product)->delete();
            ProductDescription::where('product_id', $product)->delete();
            ProductImages::where('product_id', $product)->delete();
            ProductsAttributes::where('product_id', $product)->delete();
            ProductTo1C::where('product_id', $product)->delete();

        }
        return response()->json(['success' => 1]);
    }

    public function discountList(Request $request)
    {
        $sales = Sale::where('active', true)->get();
        if (empty($sales)) {
            return view('admin.products.sales', ['sales']);
        }

        if ($request->ajax()) {
            foreach ($sales as $key => $val){
                $sales[$key]['number'] = $key+1;
            }
            return Datatables::of($sales)
                ->editColumn('title', function ($row) {
                    return $row->title;
                })
                ->editColumn('first_date', function ($row) {
                    return date('d.m.Y' , strtotime($row->first_date));
                })
                ->editColumn('last_date', function ($row) {
                    return date('d.m.Y' , strtotime($row->last_date));
                })
                ->editColumn('percent', function ($row) {
                    return $row->percent;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" data-toggle="modal" id="' . ("sales_change" . $row->id) .'" data-target="#change_sales" data-id="' . $row->id . '" data-title="' . $row->title . '"  data-start_date="' . date('Y-m-d', strtotime($row->first_date)) . '" data-end_date="' .  date('Y-m-d', strtotime($row->last_date)) . '" data-percent="' . $row->percent . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $sales = Sale::all();

        return view('admin.products.sales', ['sales' => $sales]);
    }

    public function addSale(DiscountCreateRequest $request)
    {
        $sale = Sale::create([
            'title' => $request->title,
            'first_date' => $request->first_date,
            'last_date' => $request->last_date,
            'percent' => $request->percent
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно добавили скидку'
        ]);
    }

    public function editSale(DiscountUpdateRequest $request)
    {
        $sale = Sale::find($request->id);

        if(empty($sale)){
            return response()->json([
                'success' => 1,
                'message' => 'Скидка не найдена'
            ], 404);
        }
        $sale->title = $request->title;
        $sale->first_date = $request->first_date;
        $sale->last_date = $request->last_date;
        $sale->percent = $request->percent;
        $sale->save();

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно изменили скидку'
        ]);
    }

    public function deleteSale(Request $request)
    {
        // Получаем статус 0 - проверка(если все хорошо, тогда удаение), 1 - удаление
        $status = $request->status;

        // Если статус равен 0
        if ($status == 0) {
            if ($request->checked != 0) {

                // Если родительских категорий нет, то просто удаляем
                foreach ($request->checked as $catId) {
                    $product = Product::where('sale_id', $catId)->update([
                        'sale_id' => null,
                        'price_with_sale' => null,
                    ]);

                    $sale = Sale::where('id', (int)$catId)->update(['active' => 0]);
                }
                return response()->json([
                    'accepted' => 'Скидки успешно удалены'
                ]);
            }

            // Если ничего не выбрано, отправляем уведомление
            return response()->json([
                'error' => "Выберите хотя бы 1 скидку"
            ]);
        }

        // Если статус равен 1
        if ($status == 1) {
            if ($request->checked != 0) {
                $values = []; // Переменная для хранения id родительских категорий (удаление из селекта в модальном окне)
                foreach ($request->checked as $catId) {
                    $sale = Sale::where('id', (int)$catId)->update(['active' => 0]);

                    // Если категория родительская, удаляем все дочерние
                    if ($sale != null) {

                        $sale->update(['active' => 0]);
                    }
                }

                // Если категорий не осталось, возвращаем false и перезагружаем страницу
                if (count(Sale::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                // Если все прошло успешно, возвращаем наобходимые данные
                return response()->json([
                    'accepted' => 'Скидки успешно удалены',
                    'status' => true,
                ]);
            } else {
                // Если ничего не выбрано, отправляем уведомление
                return response()->json([
                    'error' => "Выберите хотя бы 1 скидку"
                ]);
            }
        }
    }

    public function discountGlobalList(Request $request)
    {
        $sales = GlobalSales::where('active', true)->get();
        if (empty($sales)) {
            return view('admin.products.globalSales', ['sales']);
        }

        if ($request->ajax()) {
            foreach ($sales as $key => $val){
                $sales[$key]['number'] = $key+1;
            }
            return Datatables::of($sales)
                ->editColumn('sum_modal', function ($row) {
                    return $row->sum_modal;
                })
                ->editColumn('procent_modal', function ($row) {
                    return $row->procent_modal;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" data-toggle="modal" id="' . ("sales_change" . $row->id) .'" data-target="#change_sales" data-id="' . $row->id . '" data-sum="' . $row->sum_modal . '"  data-percent="' . $row->procent_modal . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $sales = GlobalSales::all();

        return view('admin.products.globalSales', ['sales' => $sales]);
    }

    public function addGlobalSale(DiscountGlobalCreateRequest $request)
    {
        $sale = GlobalSales::create([
            'sum_modal' => $request->sum,
            'procent_modal' => $request->percent
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно добавили  глобальную скидку'
        ]);
    }

    public function editGlobalSale(DiscountGlobalUpdateRequest $request)
    {
        $sale = GlobalSales::find($request->id);

        if(empty($sale)){
            return response()->json([
                'success' => 1,
                'message' => 'Глобальная скидка не найдена'
            ], 404);
        }
        $sale->sum_modal = $request->sum;
        $sale->procent_modal = $request->percent;
        $sale->save();

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно изменили глобальную скидку'
        ]);
    }

    public function deleteGlobalSale(Request $request)
    {
        // Получаем статус 0 - проверка(если все хорошо, тогда удаение), 1 - удаление
        $status = $request->status;

        // Если статус равен 0
        if ($status == 0) {
            if ($request->checked != 0) {

                // Если родительских категорий нет, то просто удаляем
                foreach ($request->checked as $catId) {
                    $sale = GlobalSales::where('id', (int)$catId)->update(['active' => 0]);
                }
                return response()->json([
                    'accepted' => 'Глобальные скидки успешно удалены'
                ]);
            }

            // Если ничего не выбрано, отправляем уведомление
            return response()->json([
                'error' => "Выберите хотя бы 1 глобальную скидку"
            ]);
        }

        // Если статус равен 1
        if ($status == 1) {
            if ($request->checked != 0) {
                $values = []; // Переменная для хранения id родительских категорий (удаление из селекта в модальном окне)
                foreach ($request->checked as $catId) {
                    $sale = GlobalSales::where('id', (int)$catId)->update(['active' => 0]);

                    // Если категория родительская, удаляем все дочерние
                    if ($sale != null) {

                        $sale->update(['active' => 0]);
                    }
                }

                // Если категорий не осталось, возвращаем false и перезагружаем страницу
                if (count(GlobalSales::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                // Если все прошло успешно, возвращаем наобходимые данные
                return response()->json([
                    'accepted' => 'Глобальные cкидки успешно удалены',
                    'status' => true,
                ]);
            } else {
                // Если ничего не выбрано, отправляем уведомление
                return response()->json([
                    'error' => "Выберите хотя бы 1 глобальную скидку"
                ]);
            }
        }
    }

    public function discountGroupList(Request $request)
    {
        $sales = GroupSale::where('active', true)->get();
        if (empty($sales)) {
            return view('admin.products.groupSales', ['sales']);
        }

        if ($request->ajax()) {
            foreach ($sales as $key => $val){
                $sales[$key]['number'] = $key+1;
            }
            return Datatables::of($sales)
                ->editColumn('sum', function ($row) {
                    return $row->sum;
                })
                ->editColumn('percent', function ($row) {
                    return $row->percent;
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" data-toggle="modal" id="' . ("sales_change" . $row->id) .'" data-target="#change_sales" data-id="' . $row->id . '" data-sum="' . $row->sum . '"  data-percent="' . $row->percent . '" name="change" class="btn btn-outline-dark fa fa-wrench"></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $sales = GroupSale::all();

        return view('admin.products.groupSales', ['sales' => $sales]);
    }

    public function addGroupSale(DiscountGroupCreateRequest $request)
    {
        $sale = GroupSale::create([
            'sum' => $request->sum,
            'percent' => $request->percent
        ]);

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно добавили  групповую скидку'
        ]);
    }

    public function editGroupSale(DiscountGroupUpdateRequest $request)
    {
        $sale = GroupSale::find($request->id);

        if(empty($sale)){
            return response()->json([
                'success' => 1,
                'message' => 'Групповая скидка не найдена'
            ], 404);
        }
        $sale->sum = $request->sum;
        $sale->percent = $request->percent;
        $sale->save();

        return response()->json([
            'success' => 1,
            'message' => 'Вы успешно изменили групповую скидку'
        ]);
    }

    public function deleteGroupSale(Request $request)
    {
        // Получаем статус 0 - проверка(если все хорошо, тогда удаение), 1 - удаление
        $status = $request->status;

        // Если статус равен 0
        if ($status == 0) {
            if ($request->checked != 0) {

                // Если родительских категорий нет, то просто удаляем
                foreach ($request->checked as $catId) {
                    $sale = GroupSale::where('id', (int)$catId)->update(['active' => 0]);
                }
                return response()->json([
                    'accepted' => 'Групповые скидки успешно удалены'
                ]);
            }

            // Если ничего не выбрано, отправляем уведомление
            return response()->json([
                'error' => "Выберите хотя бы 1 групповую скидку"
            ]);
        }

        // Если статус равен 1
        if ($status == 1) {
            if ($request->checked != 0) {
                $values = []; // Переменная для хранения id родительских категорий (удаление из селекта в модальном окне)
                foreach ($request->checked as $catId) {
                    $sale = GroupSale::where('id', (int)$catId)->update(['active' => 0]);

                    // Если категория родительская, удаляем все дочерние
                    if ($sale != null) {

                        $sale->update(['active' => 0]);
                    }
                }

                // Если категорий не осталось, возвращаем false и перезагружаем страницу
                if (count(GroupSale::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                // Если все прошло успешно, возвращаем наобходимые данные
                return response()->json([
                    'accepted' => 'Групповые cкидки успешно удалены',
                    'status' => true,
                ]);
            } else {
                // Если ничего не выбрано, отправляем уведомление
                return response()->json([
                    'error' => "Выберите хотя бы 1 групповую скидку"
                ]);
            }
        }
    }
}
