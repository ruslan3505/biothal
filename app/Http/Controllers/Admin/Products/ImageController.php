<?php

namespace App\Http\Controllers\Admin\Products;

use App\Http\Controllers\Controller;
use App\Models\Admin\Products\Product;
use App\Models\Admin\Products\ProductImages;
use App\Models\ImageGlobal;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\
{
    ValidImgRequest,
    Images\Delete as ImageDeleteRequest
};

use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->input('title_image'))) {
            $title = $request->input('title_image');
            $images = Image::where('name', 'like', '%' . $title . '%')->paginate(15);
        } else {
            $images = Image::paginate(15);
        }

        $imagesGlobal = [];
        $imagesGlobalAll = ImageGlobal::all();
        // Если нет изображений, выводим подсказку
//        if (count($imagesAll) == 0) {
//            return view('admin.images.index', ['images' => null]);
//        }
//        if (count($imagesGlobalAll) == 0) {
//            return view('admin.images.index', ['imagesGlobal' => null]);
//        }

        $n = 0; // Номер группы
        $i = 0; // Итератор
        $imageS = [];//Обновляю массив
        foreach ($imagesGlobalAll as $image) {
            $imageS[$i + 1] = $image;
            $i++;
            $imagesGlobal[$n] = $imageS;
            if ($i % 4 == 0) {
                $imageS = null;
                $n++;
            }
        }

        return view('admin.images.index', [
            'images' => $images,
            'imagesGlobal' => $imagesGlobal,
        ]);
    }

    public function banner()
    {
        $images = [];
        $imagesGlobal = [];
        $imagesAll = Image::all();
        $imagesGlobalAll = ImageGlobal::where('parent_id')->get();
        // Если нет изображений, выводим подсказку
//        if (count($imagesAll) == 0) {
//            return view('admin.images.index', ['images' => null]);
//        }
//        if (count($imagesGlobalAll) == 0) {
//            return view('admin.images.index', ['imagesGlobal' => null]);
//        }

        $n = 0; // Номер группы
        $i = 0; // Итератор
        // Делаю групы по 4 изображения, для удобного размещения на форме
        foreach ($imagesAll as $image) {
            $imageS[$i + 1] = $image;
            $i++;
            $images[$n] = $imageS;
            if ($i % 5 == 0) {
                $imageS = null;
                $n++;
            }
        }

        $n = 0; // Номер группы
        $i = 0; // Итератор
        $imageS = [];//Обновляю массив
        foreach ($imagesGlobalAll as $image) {
            $imageS[$i + 1] = $image;
            $i++;
            $imagesGlobal[$n] = $imageS;
            if ($i % 5 == 0) {
                $imageS = null;
                $n++;
            }
        }

        return view('admin.images.banner', [
            'images' => $images,
            'imagesGlobal' => $imagesGlobal,
        ]);
    }

    public function addImage(ValidImgRequest $request)
    {
        // Проверяем есть ли файл
        if (!$request->hasFile('img')) {
            return redirect()->route('admin.images.page');
        } else {
            foreach($request->file('img') as $img){
                $name = $img->getClientOriginalName();

                // Помещаем файл в репозиторий
                $img->move(public_path("storage/img/products"), $name);
                // Добавляем файл в базу
                Image::create([
                    'name' => $name
                ]);

            }
            return redirect()->route('admin.images.page');
        }
    }

    public function addGlobalImage(ValidImgRequest $request)
    {

        // Проверяем есть ли файл
        if (!$request->hasFile('img2') && !$request->hasFile('img_mobile')) {
            return redirect()->route('admin.images.banner');
        }
        $name_desktop = $request->file('img2')->getClientOriginalName();
        $name_mobile = $request->file('img_mobile')->getClientOriginalName();
        // Помещаем файл в репозиторий
        $request->file('img2')->move(public_path("storage/img/carousel"), $name_desktop);
        $request->file('img_mobile')->move(public_path("storage/img/carousel"), $name_mobile);
        $href = $request->input('baner_url');
        // Добавляем файл в базу
        $image_desktop = ImageGlobal::create([
            'name' => $name_desktop,
            'href' => $href
        ]);

        $image_mobile = ImageGlobal::create([
            'name' => $name_mobile,
            'parent_id' => $image_desktop->id,
            'href' => !empty($href) ? $href : ''
        ]);

        return redirect()->route('admin.images.banner');
    }

    public function deleteImage(ImageDeleteRequest $request)
    {
        foreach ($request->checked as $imgId) {
            $image = Image::where('id', (int)$imgId)->first();
            $subImage = ProductImages::where('image', (int)$imgId)->delete();
            $products = Product::where('image_id', (int)$imgId)->get();
            $pathToYourFile = public_path("storage/img/products/".$image->name);
            if(file_exists($pathToYourFile))
            {
                unlink($pathToYourFile);
            }
            $image->delete();
            if(!empty($subImage)){
                $subImage->delete();
            }
            if(!empty($products)){
                foreach($products as $product){
                    $product->image_id = null;
                    $product->save();
                }
            }
        }
        return true;
    }

    public function deleteGlobalImage(ImageDeleteRequest $request)
    {
        foreach ($request->checked as $imgId2) {
            $image2 = ImageGlobal::where('id', (int)$imgId2)->first();
            $image_child = ImageGlobal::where('parent_id', (int)$imgId2)->first();
            $pathToYourFile = public_path("storage/img/carousel/".$image2->name);
            $pathToYourFileChild = '';
            if(!empty($image_child)){
                $pathToYourFileChild = public_path("storage/img/carousel/".$image_child->name);
            }
            if(file_exists($pathToYourFile) && !empty($pathToYourFileChild) && file_exists($pathToYourFileChild))
            {
                unlink($pathToYourFile);
                unlink($pathToYourFileChild);
            } elseif(file_exists($pathToYourFile)){
                unlink($pathToYourFile);
            }
            $image2->delete();
            $image_child->delete();
        }
        return true;
    }

    public function getImages(Request $request)
    {
        if (!empty($request->input('title_image'))) {
            $title = $request->input('title_image');
            $images = Image::where('name', 'like', '%' . $title . '%');
        } else {
            $images = Image::query();
        }
        return response()->json([
            'data' => $images->paginate($request->input('count', 12))
        ]);
    }

    public function changeImageActive(Request $request)
    {
        if (!empty($request->get('id'))) {

            ImageGlobal::where('id', $request->get('id'))->update(['active' => $request->get('active')]);
            ImageGlobal::where('parent_id', $request->get('id'))->update(['active' => $request->get('active')]);

            return response()->json([
                'message' => 'Баннер ' . ($request->get('active') ? 'включен' : 'выключен')
            ], 200);
        } else {
            return response()->json([
                'message' => 'Ошибка'
            ], 404);
        }
    }


}
