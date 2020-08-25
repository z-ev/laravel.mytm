<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name' => 'sometimes|min:3|max:30',
            'email' => 'sometimes|email|unique:users',
            'password' => 'sometimes',
            'password_c' => 'same:password',
            'old_password' => 'sometimes:password'


        ];
    }
}
