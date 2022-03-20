<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentUpdateFormRequest extends FormRequest
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
        $id = $this->request->get('id');
        return [
            'name' => 'required|min:3',
            'dob' => 'required',
            'code' => 'required|min:8|max:8|unique:students,code,' . $id,
            'phone' => 'required|regex:/^[0-9]{10}$/|unique:students,phone,' 
                        . $id,
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:students,email,' . $id,
            'id_grade' => 'required',
            'status' => 'required',
        ];
    }
}
