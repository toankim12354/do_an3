<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUpdateFormRequest extends FormRequest
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
            'phone' => 'required|regex:/^[0-9]{10}$/|unique:admins,phone,' 
                        . $id,
            'gender' => 'required',
            'address' => 'required',
            'email' => 'required|email|unique:admins,email,' . $id,
            'status' => 'required|gte:is_super',
            'is_super' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'status.gte' => 'Super admin cannot disabled',
        ];
    }
}
