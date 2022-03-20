<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeacherUpdateFormRequest extends FormRequest
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
        // $id = $this->request->get('id');
        return [
            'name' => 'required|min:3',
            'dob' => 'required',
            'phone' => [
                'required',
                'regex:/^[0-9]{10}$/',
                Rule::unique('teachers')->ignore($this->teacher_manager),
            ],
            'gender' => 'required',
            'address' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('teachers')->ignore($this->teacher_manager)
            ],
            'status' => 'required',
        ];
    }
}
