<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherStoreFormRequest extends FormRequest
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
            'name' => 'required|min:3',
            'dob' => 'required',
            'phone' => 'required|regex:/^[0-9]{10}$/|unique:teachers',
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:teachers',
            'password' => 'required|min:8|max:32'
        ];
    }
}
