<?php

namespace App\Http\Requests\Admin\Distribution;

use Illuminate\Foundation\Http\FormRequest;

class Create extends FormRequest
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
            'email' => 'required|email|unique:emails_for_email_newsletter',
            'group_id' => 'required|numeric',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'Вы не ввели електронную почту<br>',
            'email.email' => 'Вы ввели некорректный адрес электронной почты<br>',
            'email.unique' => 'Такая почта уже существует<br>',
            'group_id.required' => 'Поле "Группа" - обязательное!<br>',
            'group_id.numeric' => 'Поле "Группа" должно быть выбрано!<br>',
        ];
    }
}
