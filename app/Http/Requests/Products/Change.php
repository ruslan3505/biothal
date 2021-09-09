<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class Change extends FormRequest
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
            'link' => 'max:150|regex:/^[a-zA-Z_-]+$/',
            'name' => 'max:255|required',
            'meta_description' => 'max:299',
            'meta_keywords' => 'max:250',
            'image_radio' => 'required|integer|gt:0',
            'product_id' => 'integer|gt:0',
        ];
    }

    public function messages()
    {
        return [
            'link.regex' => 'Ссылка может содержать только буквы английского алфавита или знаки - и _',
            'link.max' => 'Максимальная длинна ссылки, не может привышать 150 символов!',
            'name.max' => 'Максимальная длинна название, не может привышать 255 символов!',
            'meta_description.max' => 'Максимальная длинна мета тега "description", не может привышать 299 символов!',
            'meta_keywords.max' => 'Максимальная длинна мета тегов "keyword", не может привышать 250 символов!',
            'description.max' => 'Максимальная длинна "описания", не может привышать 10000 символов!',
            'name.required' => 'Поле "Название" - обязательное!',
            'product_id.gt' => 'Не нужно ничего менять на странице x2!',
            'image_radio.gt' => 'Такой картинки не существует',
            'image_radio.required' => 'Выберите картинку!',
            'image_radio.integer' => '?',
            'product_id.integer' => 'Не нужно ничего менять на странице!',
        ];
    }
}
