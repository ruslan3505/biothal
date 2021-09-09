<?php

namespace App\Http\Requests\Products\Sales;

use Illuminate\Foundation\Http\FormRequest;

class SetSale extends FormRequest
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
            'saleId' => 'required|integer',
            'id' => 'required|array',
            'id.*' => 'integer',
        ];
    }

    public function messages()
    {
        return [
            'saleId.integer' => 'Выберите скидку из списка!',
            'saleId.required' => 'Скидка должна существовать.',
            'id.array' => 'Тебе здесь не рады.',
            'id.required' => 'Выберите хотя бы 1 продукт',
            'id.*.integer' => 'id должен быть натуральным числом',
        ];
    }
}
