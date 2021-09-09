<?php

namespace App\Http\Controllers\Admin\Categories;

use App\Http\Requests\Categories\Add as CategoryAddRequest;
use App\Http\Requests\Categories\Delete as CategoryDeleteRequest;
use App\Http\Controllers\Controller;
use App\Models\Admin\Accessories\Accessories;
use App\Models\Categories;
use Illuminate\Http\Request;
use DataTables;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{

    public function index(Request $request)
    {
        $categories = Categories::all();
        if (count($categories) == 0) {
            return view('admin.categories.index', ['categories' => null]);
        }

        if ($request->ajax()) {
            foreach ($categories as $key => $val){
                $categories[$key]['number'] = $key+1;
            }

            return Datatables::of($categories)
                ->editColumn('parent_id', function ($row) {
                    return $row->parent_id != null ? $row->Category->title : "Без родительской категории";
                })
                ->editColumn('type_category', function ($row) {
                    return $row->type_category == 0 ? "Для товаров" : "Информационная";
                })
                ->editColumn('is_demand', function ($row) {
                    return $row->is_demand == 1 ? "Потребность" : "Категория";
                })
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btn = '<button type="button" onClick="getDescriptionText(' . $row->id . ')" data-toggle="modal" data-target="#change_categ" name="tofita" data-id="' . $row->id . '" data-title="' . $row->title . '" data-demand="' . $row->is_demand . '" data-order="' . $row->ordering . '" data-parent="' . ($row->parent_id != null ? $row->Category->title : "null") . '" data-parent-id="' . ($row->parent_id != null ? $row->parent_id : "null") . '" id="' . ("category_change" . $row->id) .'" data-type-category-change="' . $row->type_category . '" data-seo-title="' . $row->seo_title . '" data-bottom="' . $row->bottom . '" class="btn btn-outline-dark fa fa-wrench"></button>';

                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $categories = Categories::where([['parent_id', null], ['type_category', 0]])->get();

        return view('admin.categories.index', ['categories' => $categories]);
    }

    public function addCategory(CategoryAddRequest $request)
    {
        $categoriesCount = count(Categories::all());
        $select = $request->parent_id == "NoCategory" ? null : $request->parent_id;
        $type_category = $request->type_category == "forProduct" ? 0 : 1;

        Categories::create([
            'parent_id' => $select,
            'title' => $request->title,
            'ordering' => $request->ordering,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'is_demand' => 0,
            'type_category' => $type_category,
            'bottom' => $request->bottom_view
        ]);

        if ($categoriesCount == 0){
            return response()->json([
                'message' => false,
            ]);
        }

        $category = Categories::all()->last();

        // Проверяем создана ли РОДИТЕЛЬСКАЯ категория, если да - добавляем в модалку в селект
        if ($category->parent_id == null) {
            return response()->json([
                'message' => "Категория успешно добавлена",
                'value' => $category->title,
                'parent' => $category->title,
            ]);
        }

        return response()->json([
            'message' => "Категория успешно добавлена",
            'value' => false,
        ]);
    }

    public function deleteCategory(CategoryDeleteRequest $request)
    {
        // Получаем статус 0 - проверка(если все хорошо, тогда удаение), 1 - удаление
        $status = $request->status;

        // Если статус равен 0
        if ($status == 0) {
            if ($request->checked != 0) {
                foreach ($request->checked as $catId) {
                    $category = Categories::where('id', (int)$catId)->first();

                    // Проверяем, выбрана ли родительская категория, если да, возвращаем модалку с подтверждением
                    if ($category->is_demand == 0) {
                        return response()->json([
                            'warning' => [
                                'title' => 'Вы уверены что хотите удалить эту категорию?',
                                'text' => 'Это приведет к удалению дочерних категорий и потребностей!',
                            ]
                        ]);
                    }
                }

                // Если родительских категорий нет, то просто удаляем
                foreach ($request->checked as $catId) {
                    $category = Categories::where('id', (int)$catId)->first();
                    $category->delete();
                }
                return response()->json([
                    'accepted' => 'Категории успешно удалены'
                ]);
            }

            // Если ничего не выбрано, отправляем уведомление
            return response()->json([
                'error' => "Выберите хотя бы 1 категорию"
            ]);
        }

        // Если статус равен 1
        if ($status == 1) {
            if ($request->checked != 0) {
                $values = []; // Переменная для хранения id родительских категорий (удаление из селекта в модальном окне)
                foreach ($request->checked as $catId) {
                    $category = Categories::where('id', (int)$catId)->first();

                    // Если категория родительская, удаляем все дочерние
                    if ($category != null) {
                        if ($category->is_demand == 0) {
                            $values[] += $category->id;
                            $sonsCategories = Categories::where('parent_id', $category->id)->get();
                            foreach ($sonsCategories as $son) {
                                $son->delete();
                            }
                        }

                        $values[] += $category->id;
                        $sonsAccessories = Accessories::where('parent_id', $category->id)->get();
                        foreach ($sonsAccessories as $son) {
                            $son->delete();
                        }

                        $category->delete();
                    }
                }

                // Если категорий не осталось, возвращаем false и перезагружаем страницу
                if (count(Categories::all()) == 0){
                    return response()->json([
                        'status' => false,
                    ]);
                }

                // Если все прошло успешно, возвращаем наобходимые данные
                return response()->json([
                    'accepted' => 'Категории успешно удалены',
                    'value' => isset($values) ? $values : false,
                    'status' => true,
                ]);
            } else {
                // Если ничего не выбрано, отправляем уведомление
                return response()->json([
                    'error' => "Выберите хотя бы 1 категорию"
                ]);
            }
        }
    }

    public function changeCategory(CategoryAddRequest $request)
    {
        $parentId = $request->parent_id == "NoCategory" ? null : $request->parent_id;
        $type_category = $request->type_category == "forProduct" ? 0 : 1;
        $category = Categories::find($request->id);

        $category->update([
            'parent_id' => $parentId,
            'title' => $request->title,
            'ordering' => $request->ordering,
            'seo_title' => $request->seo_title,
            'seo_description' => $request->seo_description,
            'is_demand' => $request->is_demand,
            'type_category' => $type_category,
            'bottom' => $request->bottom_view
        ]);

        return response()->json([
            'message' => "Категория успешно изменена"
        ]);
    }
    public function getAllCategory()
    {
        $category = Categories::All();
        return view('app', compact('category'));
    }

    public function getText(Request $request)
    {
        $category = Categories::where('id', $request->id)->first();
        if(empty($category)){
            return response()->json([
                'message' => "Категория не найдена"
            ], 404);
        } else {
            return response()->json([
                'text' => $category->seo_description
            ], 200);
        }
    }
}
