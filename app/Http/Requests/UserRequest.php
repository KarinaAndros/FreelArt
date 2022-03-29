<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string|max:50|regex:/[А-Яа-яЁё]/u',
            'surname' => 'required|string|max:50|regex:/[А-Яа-яЁё]/u',
            'patronymic' => 'nullable|string|max:50|regex:/[А-Яа-яЁё]/u',
            'login' => 'required|string|max:50|regex:/[A-Za-z]/u|unique:users',
            'email' => 'required|string|max:50|email|unique:users',
            'password' => 'required|string|max:50|min:6|confirmed',
            'password_confirmation' => 'required|string|max:50|min:6',
            'phone' => 'nullable|string|max:50',
            'role' => 'required',
            'avatar' => 'nullable|file|image|max:1024',
            'rule' => 'accepted',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'обязательно для заполнения',
            'name.max' => 'не более 50 символов',
            'name.regex' => 'только символы кириллицы',
            'surname.max' => 'не более 50 символов',
            'surname.regex' => 'только символы кириллицы',
            'patronymic.max' => 'не более 50 символов',
            'patronymic.regex' => 'только символы кириллицы',
            'surname.required' => 'обязательно для заполнения',
            'login.required' => 'обязательно для заполнения',
            'login.max' => 'не более 50 символов',
            'login.regex' => 'только символы латиницы',
            'login.unique' => 'логин уже используется',
            'email.max' => 'не более 50 символов',
            'email.email' => 'некоректный тип',
            'email.unique' => 'email уже используется',
            'email.required' => 'обязательно для заполнения',
            'password.required' => 'обязательно для заполнения',
            'password.max' => 'не более 50 символов',
            'password.min' => 'не менее 6 символов',
            'password.confirmed' => 'пароль не подтверждён',
            'password_confirmation.required' => 'обязательно для заполнения',
            'phone.max' => 'не более 50 символов',
            'role.required' => 'обязательно для заполнения',
            'rule.accepted' => 'обязательно для потверждения',
            'avatar.file' => 'должен быть выбран файл',
            'avatar.image' => 'должно быть выбрано изображение',
            'avatar.max' => 'не более 1КБ',
        ];
    }
}
