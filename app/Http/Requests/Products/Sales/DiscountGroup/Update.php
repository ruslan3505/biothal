<?php

namespace App\Http\Requests\Products\Sales\DiscountGroup;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
            'sum' => 'required|numeric|min:1',
            'percent' => 'required|numeric|min:1|max:100',
        ];
    }

    public function messages()
    {
        return [
            'sum.required' => 'Поле "Сумма" - обязательное!<br>',
            'sum.min' => 'Значение поля "Сумма", не может быть меньше 1!<br>',
            'percent.required' => 'Поле "Процент" - обязательное!<br>',
            'percent.numeric' => 'Поле "Процент" должно быть числом!<br>',
            'percent.max' => 'Поле "Процент" не может быть больше 100%!<br>',
            'percent.min' => 'Значение поля "Процент", не может быть меньше 1!<br>'
        ];
    }
}
