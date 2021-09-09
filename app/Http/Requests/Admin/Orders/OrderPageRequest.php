<?php

namespace App\Http\Requests\Admin\Orders;

use Illuminate\Foundation\Http\FormRequest;

class OrderPageRequest extends FormRequest
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
            'filter_order_id' => 'nullable|numeric',
            'filter_customer' => 'nullable|size:100000',
            'filter_total' => 'nullable|numeric',
            'filter_date_added' => 'nullable|date',
            'filter_date_modified' => 'nullable|date',
        ];
    }
}
