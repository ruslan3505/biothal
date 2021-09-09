<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

class Update extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'price' => 'required',
            'quantity' => 'required',
            'minimum' => 'required',
            'sort_order' => 'required',
            'product_description.*.description' => 'required',
            'product_description.*.name' => 'max:255|required',
            'product_description.*.meta_description' => 'max:299',
            'product_description.*.meta_keywords' => 'max:250',
            'product_description.*.meta_title' => 'required',
            'categoryProducts.category_id' => 'integer',
            'product_apt.*.tab_title' => 'required',
            'product_apt.*.tab_desc' => 'required',
            'product_apt.*.sort_order' => 'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'price.required' => 'Поле "Цена" - обязательное!',
            'quantity.required' => 'Поле "Количество" - обязательное!',
            'minimum.required' => 'Поле "Минимальное количество" - обязательное!',
            'sort_order.required' => 'Поле "Порядок сортировки" - обязательное!',
            'product_description.*.meta_description.max' => 'Максимальная длинна мета тега "description", не может привышать 299 символов!',
            'product_description.*.meta_keywords.max' => 'Максимальная длинна мета тегов "keyword", не может привышать 250 символов!',
            'product_description.*.description.max' => 'Максимальная длинна "описания", не может привышать 10000 символов!',
            'product_description.*.description.required' => 'Максимальная длинна "описания", не может привышать 10000 символов!',
            'product_description.*.name.required' => 'Поле "Название" - обязательное!',
            'product_description.*.meta_title.required' => 'Поле "Мета-тег Title" - обязательное!',
            'categoryProducts.category_id.integer' => 'Поле "Категория товара" - обязательное! Во вкладке "Связи"',
            'product_apt.*.tab_title.required' => 'Поле "Заголовок" - обязательное! Во вкладке "Вкладки"',
            'product_apt.*.tab_desc.required' => 'Поле "Содержимое" - обязательное! Во вкладке "Вкладки"',
            'product_apt.*.sort_order.required' => 'Поле "Порядок" - обязательное! Во вкладке "Вкладки"',
        ];
    }
}
