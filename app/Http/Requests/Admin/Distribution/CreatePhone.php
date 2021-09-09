<?php

namespace App\Http\Requests\Admin\Distribution;

use Illuminate\Foundation\Http\FormRequest;

class CreatePhone extends FormRequest
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
            'phone' => 'required|unique:phone_for_receive',
            'group_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'phone.required' => 'Вы не ввели телефоный номер<br>',
            'phone.email' => 'Вы ввели некорректный телефоный номер<br>',
            'phone.unique' => 'Такой телефоный номер уже существует<br>',
            'group_id.required' => 'Поле "Группа" - обязательное!<br>',
            'group_id.numeric' => 'Поле "Группа" должно быть выбрано!<br>',
        ];
    }
}
