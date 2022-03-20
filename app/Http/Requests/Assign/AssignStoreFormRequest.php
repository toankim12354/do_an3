<?php

namespace App\Http\Requests\Assign;

use Illuminate\Foundation\Http\FormRequest;

class AssignStoreFormRequest extends FormRequest
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
            'id_grade.*' => 'required|numeric|min:8',
            'id_subject.*' => 'required|numeric|min:8',
            'id_teacher.*' => 'required|numeric|min:8',
        ];
    }
}
