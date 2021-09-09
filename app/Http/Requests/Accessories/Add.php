<?php

namespace App\Http\Requests\Accessories;

use App\Models\Admin\Accessories\Accessories;
use App\Models\Categories;
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
            'parent_id' => 'required|integer|exists:'.Categories::class.',id',
            'title' => 'required',
            'ordering' => 'required|integer|between:1,9999',
        ];
    }

    public function messages()
    {
        return [
            'parent_id.required' => 'Поле "Родительская категория" должно быть выбранным!<br>',
            'parent_id.exists' => 'Такой категории не существует!<br>',
            'title.required' => 'Поле "Название категории" не должно быть пустым!<br>',
            'ordering.required' => 'Поле "Порядок сортировки" не должно быть пустым!<br>',
            'ordering.integer' => 'Поле "Порядок сортировки" должно быть числом!<br>',
            'ordering.between' => 'Поле "Порядок сортировки" должно быть в пределах от :min до :max!<br>',
            'ordering.max' => 'Поле "Порядок сортировки" должно быть как минимум :max!<br>',
          ];
    }
}
