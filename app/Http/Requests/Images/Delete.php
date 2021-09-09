<?php

namespace App\Http\Requests\Images;

use Illuminate\Foundation\Http\FormRequest;

class Delete extends FormRequest
{

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
            'checked.*' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'checked.required' => 'Выберите хотя бы одно изображение!!'
        ];
    }
}
