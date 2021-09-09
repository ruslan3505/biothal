<?php

namespace App\Http\Requests\Products\Sales;

use Illuminate\Foundation\Http\FormRequest;

class Delete extends FormRequest
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
            'id' => 'required|array',
            'id.*' => 'integer'
        ];
    }

    public function messages()
    {
        return [
            'id.array' => 'Попытка изменить внутренние файлы, попробуйте перезагрузить стриницу',
            'id.required' => 'Выберите хотя бы 1 скидку',
            'id.*.integer' => 'Id должен быть натуральным числом',
        ];
    }
}
