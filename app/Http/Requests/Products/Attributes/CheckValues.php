<?php

namespace App\Http\Requests\Products\Attributes;

use Illuminate\Foundation\Http\FormRequest;

class CheckValues extends FormRequest
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
            'title' => 'required|max:255',
            'value' => 'required|max:255',
            'id' => 'integer|min:1',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Введите название атрибута!',
            'value.required' => 'Введите значение атрибута!',
            'title.max' => 'Максимальная длинна названия :max символов!',
            'value.max' => 'Максимальная длинна значения :max символов!',
            'id.integer' => 'Не нужно ничего менять на странице!',
            'id.min' => 'Не нужно ничего менять на странице x2!',
        ];
    }
}
