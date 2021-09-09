<?php

namespace App\Http\Requests\Products\Sales\Discount;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

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
            'title' => 'required|max:255',
            'first_date' => 'required|date',
            'last_date' => 'required|date',
            'percent' => 'required|numeric|min:1|max:100',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Поле "Название скидки" - обязательное!<br>',
            'title.max' => 'Максимальная длинна поля "Название скидки", не может привышать 255 символов!<br>',
            'first_date.required' => 'Поле "Дата начала скидки" - обязательное!<br>',
            'first_date.date' => 'Поле "Дата начала скидки" введено некорректно!<br>',
            'last_date.required' => 'Поле "Дата окончания скидки" - обязательное!<br>',
            'last_date.date' => 'Поле "Дата окончания скидки" введено некорректно!<br>',
            'percent.required' => 'Поле "Процент скидки" - обязательное!<br>',
            'percent.numeric' => 'Поле "Процент скидки" должно быть числом!<br>',
            'percent.min' => 'Значение поля "Процент скидки", не может быть меньше 1!<br>',
            'percent.max' => 'Поле "Процент скидки" не может быть больше 100%!<br>'
        ];
    }
}
