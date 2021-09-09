<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Admin\Products\Product;
use App\Models\Admin\Products\ProductImages;
use App\Models\Categories;
use App\Models\CategoryProducts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{
    public function getProduct($id)
    {
        $category = CategoryProducts::where('product_id', $id)->first();

        $product_category['sub_category'] = Categories::where('id', $category['category_id'])->first();
        $parentId = $product_category['sub_category']['parent_id'] ?? null;
        $product_category['main_category'] = [];
        if(!empty($parentId)){
            $product_category['main_category'] = Categories::where([
                'id' => $product_category['sub_category']['parent_id'],
            ])->first();
        }

        if (empty($product_category['sub_category']['parent_id'])) {
            $product_category['main_category'] = [];
        } else {
            $product_category['main_category'] = Categories::where([
                'id' => $product_category['sub_category']['parent_id'],
            ])->first();
        }

        $recommendedProduct = Product::with([
                'image',
                'productDescription',
                'getSale'
            ])
            ->where([
                'is_recommended' => 1,
                'status' => 1
            ])
            ->get();

        $productDetails = Product::with([
                'image',
                'categories',
                'productDescription',
                'productApts',
                'productImages',
                'getSale',
                'stockStatus'
            ])
            ->where('id', $id)
            ->first();

        $productDescription = html_entity_decode($productDetails['productDescription']['description']);

        if(!empty($productDetails['productApts'])){
            foreach($productDetails['productApts'] as $key => $productApt){
                $productDetails['productApts'][$key]['tab_desc'] = html_entity_decode($productApt['tab_desc']);
            }
        }

        return response()->json([
            'id' => $id,
            'productDetails' => $productDetails,
            'recommendedProduct' => $recommendedProduct,
            'product_category' => $product_category,
            'description' => $productDescription
        ]);
    }

    public function getRecommendedProduct()
    {
        $recommendedProduct = Product::with([
                'image',
                'productDescription',
                'getSale'
            ])
            ->where('is_recommended', '=', 1)
            ->orderBy('stock_status_id', 'asc')
            ->get();

        return response()->json($recommendedProduct);
    }

    public function getProducts(Request $request)
    {
        foreach($request->products as $key => $elem)
        {
            $product = Product::with([
            'image',
            'categories',
            'productDescription',
            'productApts'
            ])->find($elem['id'])->toArray();
            $products[] = array_filter($product, function($element, $key){return $key !== 'quantity';},
                ARRAY_FILTER_USE_BOTH );
        }
        
        return response()->json($products);
    }
}
