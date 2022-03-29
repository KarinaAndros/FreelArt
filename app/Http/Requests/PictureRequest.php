<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PictureRequest extends FormRequest
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
            'title' => 'required|string|max:50',
            'genre_id' => 'required|exists:genres,id',
            'description' => 'nullable|string|min:10|max:300',
            'price' => 'required|numeric|min:1|max:999999',
            'discount' => 'nullable|integer|min:1|max:99',
            'size' => 'required|string|max:10',
            'writing_technique' => 'required|string|max:50',
            'img' => 'nullable|file|image|max:1024',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'обязательно для заполнения',
            'title.max' => 'не более 50 символов',
            'genre_id.required' => 'выберите жанр',
            'genre_id.exists' => 'жанр не найден',
            'description.min' => 'не менее 10 символов',
            'description.max' => 'не более 300 символов',
            'writing_technique.required' => 'обязательно для заполнения',
            'writing_technique.max' => 'не более 50 символов',
            'price.required' => 'обязательно для заполнения',
            'price.numeric' => 'поле должно содержать число',
            'price.min' => 'минимум 1 руб',
            'price.max' => 'максимум 999999 руб',
            'discount.integer' => 'поле должно содержать целочисленное значение',
            'discount.min' => 'минимум 1%',
            'discount.max' => 'максимум 99%',
            'size.required' => 'обязательно для заполнения',
            'size.max' => 'не более 10 символов',
            'img.file' => 'должен быть выбран файл',
            'img.image' => 'должно быть выбрано изображение',
            'img.max' => 'не более 1КБ',
        ];
    }
}
