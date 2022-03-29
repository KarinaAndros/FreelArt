<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplicationRequest extends FormRequest
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
            'genre_id' => 'required|exists:genres,id',
            'description' => 'required|string|min:10|max:300',
            'payment' => 'required|numeric|min:1|max:999999',
            'writing_technique' => 'required|string|max:50',
            'deadline' => 'required|date',
            'application_category_id' => 'required|exists:application_categories,id',
        ];
    }

    public function messages()
    {
        return [
          'genre_id.required' => 'выберите жанр',
          'genre_id.exists' => 'жанр не найден',
          'application_category_id.required' => 'выберите категорию',
            'application_category_id.exists' => 'категория не найдена',
          'description.required' => 'обязательно для заполнения',
          'description.min' => 'не менее 10 символов',
          'description.max' => 'не более 300 символов',
          'writing_technique.required' => 'обязательно для заполнения',
            'writing_technique.max' => 'не более 50 символов',
            'payment.required' => 'обязательно для заполнения',
            'payment.numeric' => 'поле должно содержать число',
            'payment.min' => 'минимум 1 руб',
            'payment.max' => 'максимум 999999 руб',
          'deadline.required' => 'выберите срок здачи работы',
        ];
    }
}
