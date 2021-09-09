<?php

namespace App\Http\Controllers;

use App\Models\Admin\Products\Product;
use App\Models\ImageGlobal;
use Illuminate\Support\Arr;
use App\Models\Categories;
use App\Models\CategoryProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function getCategoryProducts($id)
    {
        if($id === 'new_products'){
            $products = Product::with([
                'image',
                'productDescription',
                'getSale'
            ])
                ->where([
                    'status' => 1,
                    'is_new' => true
                ])
                ->orderBy('stock_status_id', 'ASC')
                ->paginate(12);
        } else {
            $category = Categories::where('slug', $id)->first();

            $categoryParentProducts = Categories::select('id')->where('parent_id', '=', $category['id'])->get()->toArray();

            if (!empty(Categories::where('parent_id', $category['id'])->first())) { // Проверка есть ли у родительской категории дети
                $products_ids = CategoryProducts::whereIn('category_id', Arr::pluck($categoryParentProducts, 'id'))
                    ->orderBy('product_id', 'desc')
                    ->get()->pluck('product_id')->toArray();
            } else {
                $products_ids = CategoryProducts::where('category_id', $category['id'])
                    ->orderBy('product_id', 'desc')
                    ->get()->pluck('product_id')->toArray();
            }

            $products = Product::with([
                'image',
                'productDescription',
                'getSale'
            ])
                ->where([
                    'status' => 1
                ])
                ->whereIn('id', $products_ids)
                ->orderBy('stock_status_id', 'ASC')
                ->paginate(12);

        }
        return response()->json($products);
    }

    public function getSubCategory($id, $children_id){

        $category = Categories::where('slug', $children_id)->first();
        $products_ids = CategoryProducts::select('product_id')->where([
            'category_id' => $category['id']
        ])->get();

        $products = Product::with([
                'image',
                'productDescription',
                'getSale'
            ])
            ->where([
                'status' => 1
            ])
            ->whereIn('id', $products_ids)
            ->orderBy('stock_status_id', 'ASC')
            ->paginate(12);

        return response()->json($products);
    }

    public function getImage(){
        return response()->json([
            'carouselDesktop' => ImageGlobal::where([['parent_id'], ['active', 1]])->get(),
            'carouselMobile' => ImageGlobal::where([['parent_id', '!=', null], ['active', 1]])->get()
        ]);
    }

    public function getCategoryDetails($id){
        return response()->json(Categories::where('slug', $id)->first());
    }
}
