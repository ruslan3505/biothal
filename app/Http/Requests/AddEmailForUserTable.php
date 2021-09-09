<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddEmailForUserTable extends FormRequest
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
            'email.required' => 'Вы не ввели електронную почту',
            'email.max' => 'Вы превысили лимит символов для електронной почты',
            'email.unique' => 'Ваша почта не корректна, введите другую',
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
            'email' => 'required|string|email|max:100|unique:emails_for_email_newsletter',
        ];
    }
}
