<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class GetForChange extends FormRequest
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
            'id' => 'numeric|integer'
        ];
    }

    public function messages()
    {
        return [
            'id.numeric' => 'Покажи мне цифры :р',
            'id.integer' => 'Дробный айдишник??? Лол, шта О_О',
        ];
    }
}
