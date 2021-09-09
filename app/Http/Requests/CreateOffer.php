<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOffer extends FormRequest
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
            'name.required' => 'Вы не ввели имя',
            'name.max' => 'Имя должно содержать не более чем 40 символов',
            'name.string' => 'Имя должно только буквы',
            'email.required' => 'Вы не ввели електронную почту',
            'email.email' => 'Вы ввели некорректный адрес электронной почты',
            'phone.required' => 'Вы не ввели телефоный номер',
            'text.max' => 'Сообщение должно содержать не более чем 1000 символов',
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
            'name' => 'required|string|max:40',
            'email' => 'required|email',
            'phone' => 'required|max:20',
            'text' => 'max:1000',
        ];
    }
}
