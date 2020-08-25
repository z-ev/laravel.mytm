<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
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

            'name' => 'required|min:3|max:30',
            'email' => 'required|email',
            'password' => 'required',
            'password_c' => 'required|same:password',

        ];

    }


    public function messages()
    {

        return [

            'name' => 'Введите имя пользователя',
            'name.min' => 'Минимальное имя 3 символа',
            'name.max' => 'Максимальное имя 10 символов',
            'password' => 'Введите пароль (от 8 символов)',
            'password_c' => 'Подтвердите пароль',

        ];

    }



}
