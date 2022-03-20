<?php

namespace App\Http\Requests\Assign;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AssignUpdateFormRequest extends FormRequest
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
            'id_grade' => [
                'required',
                Rule::unique('assigns')->where(function($query) {
                    return $query->where('id_subject', $this->id_subject)
                                 ->Where('id_teacher', $this->id_teacher);
                })->ignore($this->assign)
            ],
            'id_subject' => [
                'required',
                Rule::unique('assigns')->where(function($query) {
                    return $query->where('id_grade', $this->id_grade)
                                 ->Where('id_teacher', $this->id_teacher);
                })->ignore($this->assign)
            ],
            'id_teacher' => [
                'required',
                Rule::unique('assigns')->where(function($query) {
                    return $query->where('id_grade', $this->id_grade)
                                 ->Where('id_subject', $this->id_subject);
                })->ignore($this->assign)
            ],
            'status' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'id_grade.unique' => "Grade duplicate",
            'id_subject.unique' => "Subject duplicate",
            'id_teacher.unique' => "Teacher duplicate",
        ];
    }
}
