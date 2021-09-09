<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessoryProducts;
use App\Models\Admin\Accessories\Accessories;
use App\Models\Admin\Products\Product;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class AccessoryController extends Controller
{
    public function getAccessory($id){
        $accessory = Accessories::where('slug', $id)->first();
        $products_ids = AccessoryProducts::select('product_id')->where([
            'accessory_id' => $accessory['id']
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
            ->paginate(12);

        return response()->json($products);
    }

    public function getParentAccessory($id, Request $request){
        $accessoryParentProducts = Accessories::select('id')->where('parent_id', '=', $id)
            ->get()->pluck('id')->toArray();
        $products_ids = AccessoryProducts::whereIn('accessory_id', $accessoryParentProducts)
            ->orderBy('product_id', 'desc')->get()->pluck('product_id')->toArray();
        $products = Product::whereIn('id', $products_ids)->paginate('12');
        $this_accessory = Accessories::with('products')->where('id', '=', $id)->first();
        return view('allaccessory', compact('products', 'this_accessory'));
    }

    public function getAccessoryDetails($id){
        return response()->json(Accessories::where('slug', $id)->first());
    }
}
