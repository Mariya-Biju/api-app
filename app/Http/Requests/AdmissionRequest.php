<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AdmissionRequest extends FormRequest
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
        'batch_id' => [
            'required',
            'exists:batches,id',
        ],

        'students' => 'required|array|min:1', 

        'students.*.student_id' => [
            'required',
            'exists:students,id',
            Rule::unique('admissions', 'student_id')->where(function ($query) {
                return $query->where('batch_id', $this->batch_id);
            }),
        ],

        'students.*.admission_number' => [
            'required',
            'distinct',
            'unique:admissions,admission_number'
        ],

        'students.*.roll_number' => [
            'required',
            'distinct', 
            Rule::unique('admissions', 'roll_number')->where(function ($query) {
                return $query->where('batch_id', $this->batch_id);
            }),
        ],
    ];
    }
}
