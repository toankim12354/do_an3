<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LessonRequest extends FormRequest
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
            'start' => [
                'required',
                Rule::unique('lessons')->where(function($query) {
                    return $query->where('end', $this->end);
                })->ignore($this->lesson)
            ],
            'end' => [
                'required',
                Rule::unique('lessons')->where(function($query) {
                    return $query->where('start', $this->start);
                })->ignore($this->lesson)
            ]
        ];
    }
}
