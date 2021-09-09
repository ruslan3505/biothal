<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class changePass extends FormRequest
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

    public function messages()
    {
        return [
            'password.required' => 'Вы не ввели пароль',
            'password.confirmed' => 'Пароли не совпадают',
            'old_password.required' => 'Вы не ввели старый пароль',
        ];
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'old_password' => 'required|string|min:6',
            'password' => 'required|string|confirmed|min:6',
        ];
    }
}
