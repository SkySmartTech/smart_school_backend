<?php

namespace App\Http\Requests\GradeClass;

use Illuminate\Foundation\Http\FormRequest;

class GradeClassCreateRequest extends FormRequest
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
            'classId' => 'required|string|max:255',
            'class' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'gradeId' => 'nullable|string|max:255',
        ];
    }
}
