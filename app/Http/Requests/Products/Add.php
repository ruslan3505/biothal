<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class Add extends FormRequest
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
            'description' => 'max:10000',
            'link' => 'required|max:150|regex:/^[a-zA-Z_-]+$/',
            'name' => 'required|max:255',
            'meta_description' => 'max:299',
            'meta_keywords' => 'max:250',
            'image_radio' => 'required|integer|gt:0',
            'price' => 'required|numeric|between:0,99999999|regex:/^\d*(\.\d{2})?$/'
//            'product_id' => 'hello',
        ];
    }

    public function messages()
    {
        return [
            'link.max' => 'Максимальная длинна ссылки, не может привышать 150 символов!',
            'name.max' => 'Максимальная длинна название, не может привышать 255 символов!',
            'meta_description.max' => 'Максимальная длинна мета тега "description", не может привышать 299 символов!',
            'meta_keywords.max' => 'Максимальная длинна мета тегов "keyword", не может привышать 250 символов!',
            'description.max' => 'Максимальная длинна "описания", не может привышать 10000 символов!',
            'name.required' => 'Поле "Название" - обязательное!',
            'image_radio.required' => 'Выберите картинку!',
            'image_radio.integer' => '?',
            'image_radio.gt' => 'Такой картинки не существует',
            'link.regex' => 'Ссылка может содержать только буквы английского алфавита или знаки - и _',
            'link.required' => 'Пожалуйста, укажите ссылку для продукта',
            'price.regex' => 'Цена может содержать, только 2 цифры после плавающей точки',
            'price.numeric' => 'Укажите цену в цифрах!',
            'price.between' => 'Цена должна быть в пределах :min - :max',
            'price.required' => 'Укажите цену!'
        ];
    }

    //TODO: добавить проверку на хиден инпут "product_id"

//    public function messages()
//    {
//        return [
//            'product_id.hello' => 'Ahahaha, don`t touch it!'
//        ];
//    }

}
