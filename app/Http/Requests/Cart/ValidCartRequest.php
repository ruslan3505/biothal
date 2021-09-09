<?php

namespace App\Http\Requests\Cart;

use App\Models\Admin\Products\Product;
use Illuminate\Foundation\Http\FormRequest;

class ValidCartRequest extends FormRequest
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
            'count' => 'required|integer|min:1',
            'product_id' => 'required|integer|exists:'.Product::class.',id',
        ];
    }
}
