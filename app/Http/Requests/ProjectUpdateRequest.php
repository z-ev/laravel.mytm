<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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

            'title' => 'sometimes|min:5|max:300',
            'body' => 'sometimes|max:800|min:10',
            'deadline' => 'sometimes|date',
            'status' => 'sometimes|max:5|min:0',

        ];

    }
}
