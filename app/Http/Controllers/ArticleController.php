<?php

namespace App\Http\Controllers;

use App\Models\Admin\Products\InformationAttributes;
use App\Models\Admin\Products\InformationToLayout;
use App\Models\Categories;
use App\Models\ImageGlobal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ArticleController extends Controller
{

    public function getArticle($id){
        $layout_id = Categories::where('slug', $id)->first();
        $article = InformationAttributes::where('slug', $id)->first();

        if ($article !== null) {
            $article = InformationAttributes::where('slug', $id)->first();
        } else {
            $information_id = InformationToLayout::where('layout_id', $layout_id['id'])->first();
            if ($information_id !== null) {
                $article = InformationAttributes::where('information_id', $information_id['information_id'])->first();
            } else {
                $article = [];
            }
        }

        return response()->json([
            'article' => $article,
            'carouselDesktop' => ImageGlobal::where([['parent_id'], ['active', 1]])->get(),
            'carouselMobile' => ImageGlobal::where([['parent_id', '!=', null], ['active', 1]])->get()
        ]);
    }

    public function getSubArticle($id){
        $article = InformationAttributes::where('slug', $id)->first();

        $carousel = ImageGlobal::all();

        return response()->json([
            'article' => $article,
            'carousel' => $carousel
        ]);
    }
}
