<?php

namespace App\Http\Requests\Products\Sales;

use Illuminate\Foundation\Http\FormRequest;

class GlobalSalesRequest extends FormRequest
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
            'sum_modal' => 'required|integer|min:1',
            'procent_modal' => 'required|integer|between:1,100',
        ];
    }
}
