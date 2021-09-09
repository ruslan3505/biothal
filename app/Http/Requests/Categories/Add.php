<?php

namespace App\Http\Requests\Categories;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;

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
            'title' => 'required',
            'ordering' => 'required|integer|between:1,9999',
            'is_demand' => 'boolean',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Поле "Название категории" не должно быть пустым!',
            'ordering.required' => 'Поле "Порядок сортировки" не должно быть пустым!',
            'ordering.integer' => 'Поле "Порядок сортировки" должно быть числом!',
            'ordering.between' => 'Поле "Порядок сортировки" должно быть в пределах от :min до :max!    ',
            'ordering.max' => 'Поле "Порядок сортировки" должно быть как минимум :max!',
            'is_demand.boolean' => 'С чекбоксом "Категория - потребность" не должно происходить махинаций!',
        ];
    }
}
