<?php

namespace App\Http\Requests\Cart;
use App\Rules\PhoneValidation;
use Illuminate\Foundation\Http\FormRequest;

class ValidFormCheckoutRequest extends FormRequest
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
            'phone' => ['required', 'min:10','max:30', new PhoneValidation()],
            'name' => 'required|max:100',
            'LastName' => 'required|max:100',
            'region' => 'required|max:100',
            'cities' => 'required|max:100',
            'department' => 'required|max:100',
            'order_type' => 'between:1,2'
        ];
    }
}
