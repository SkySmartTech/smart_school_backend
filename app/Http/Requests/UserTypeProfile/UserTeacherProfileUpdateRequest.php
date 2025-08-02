<?php

namespace App\Http\Requests\UserTypeProfile;

use Illuminate\Foundation\Http\FormRequest;

class UserTeacherProfileUpdateRequest extends FormRequest
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
            'teacherGrades' => 'required|array',
            'teacherClass' => 'required|array',
            'subjects' => 'required|array',
            'staffNo' => 'required|string|max:255',
            'medium' => 'required|array'
        ];
    }
}
