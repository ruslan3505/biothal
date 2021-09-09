<?php

namespace App\Http\Requests\Products\Sales;

use Illuminate\Foundation\Http\FormRequest;

class Prepare extends FormRequest
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
            'id' => 'required|integer'
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'Id - отсутствует',
            'id.integer' => 'Id должно быть натуральным числом',
        ];
    }
}