<?php

namespace App\Http\Requests\Products\Information;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
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
            'description' => 'required',
            'keyword' => 'required|max:150|regex:/^[a-zA-Z_-]+$/',
            'title' => 'required|max:255',
            'meta_title' => 'required|max:255',
            'meta_description' => 'max:299',
            'meta_keywords' => 'max:250',
            'information_layout' => 'required',
            'sort_order' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'keyword.max' => 'Максимальная длинна ссылки, не может привышать 150 символов!',
            'title.max' => 'Максимальная длинна название статьи, не может привышать 255 символов!',
            'meta_title.max' => 'Максимальная длинна мета тега "title", не может привышать 255 символов!',
            'meta_description.max' => 'Максимальная длинна мета тега "description", не может привышать 299 символов!',
            'meta_keywords.max' => 'Максимальная длинна мета тегов "keyword", не может привышать 250 символов!',
            'title.required' => 'Поле "Title" - обязательное!',
            'meta_title.required' => 'Поле мета тега "title" - обязательное!',
            'keyword.regex' => 'Ссылка может содержать только буквы английского алфавита или знаки - и _',
            'keyword.required' => 'Пожалуйста, укажите ссылку для статьи',
            'information_layout.required' => 'Пожалуйста, укажите схему для статьи',
            'sort_order.required' => 'Пожалуйста, укажите порядок сортировки для статьи'
        ];
    }
}
