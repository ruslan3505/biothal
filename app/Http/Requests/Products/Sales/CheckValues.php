<?php

namespace App\Http\Requests\Products\Sales;

use Illuminate\Foundation\Http\FormRequest;

class CheckValues extends FormRequest
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
            'title' => 'required|nullable|max:255|unique:sales',
//            'first_date' => 'regex:/^[0-9\/]+$/|date_format:"m/d/Y"',
            'first_date' => 'required|date_format:"m/d/Y"',
            'last_date' => 'required|date_format:"m/d/Y"|after_or_equal:first_date',
            'percent' => 'required|integer|between:0,100',
        ];
    }

    public function messages()
    {
        return [
            'percent.between' => 'Процент должен быть числом в пределах от :min до :max.',
            'percent.required' => 'Введите процент скидки!',
            'percent.integer' => 'Процент должен быть целым числом!',
            'last_date.date_format' => 'Конечная дата должна быть выбрана из всплывающего окна!',
            'first_date.date_format' => 'Начальная дата должна быть выбрана из всплывающего окна!',
            'title.required' => 'У скидки должно быть название!',
            'title.nullable' => 'Название не может быть пустым!',
            'title.max' => 'Максимальная длинна для названия - 255 символов.',
            'last_date.after_or_equal' => 'Конечная дата не может быть меньше начальной.',
            'last_date.required' => 'Введите конечную дату.',
            'first_date.required' => 'Введите начальную дату.',
        ];
    }
}
