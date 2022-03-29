<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountRequest extends FormRequest
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
            'title' => 'required|string|max:70',
            'description' => 'required|string|min:10|max:300',
            'price' => 'required|numeric|min:1|max:999999',
            'discount' => 'nullable|integer|min:1|max:99',
            'application_category_id' => 'required|exists:application_categories,id',

        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'обязательно для заполнения',
            'description.required' => 'обязательно для заполнения',
            'title.max' => 'не более 70 символов',
            'description.min' => 'не менее 10 символов',
            'description.max' => 'не более 300 символов',
            'price.required' => 'обязательно для заполнения',
            'price.numeric' => 'поле должно содержать число',
            'price.min' => 'минимум 1 руб',
            'price.max' => 'максимум 999999 руб',
            'discount.integer' => 'поле должно содержать целочисленное значение',
            'discount.min' => 'минимум 1%',
            'discount.max' => 'максимум 99%',
            'application_category_id.required' => 'выберите категорию',
            'application_category_id.exists' => 'категория не найдена',
        ];
    }
}
