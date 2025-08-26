<?php

namespace App\Http\Requests\Marks;

use Illuminate\Foundation\Http\FormRequest;

class MarksCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'marks'                     => 'required|array',
            'marks.*.studentAdmissionNo'=> 'required|string|max:255',
            'marks.*.studentName'       => 'required|string|max:255',
            'marks.*.studentGrade'      => 'required|string|max:255',
            'marks.*.studentClass'      => 'required|string|max:255',
            'marks.*.year'              => 'required|string|max:255',
            'marks.*.term'              => 'required|string|max:255',
            'marks.*.month'             => 'nullable|string|max:255',
            'marks.*.subject'           => 'required|string|max:255',
            'marks.*.medium'            => 'nullable|string|max:255',
            'marks.*.marks'             => 'required|integer|max:100',
            'marks.*.marksGrade'        => 'nullable|string|max:255',
        ];
    }
}
