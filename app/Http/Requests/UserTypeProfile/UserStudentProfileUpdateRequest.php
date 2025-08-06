<?php

namespace App\Http\Requests\UserTypeProfile;

use Illuminate\Foundation\Http\FormRequest;

class UserStudentProfileUpdateRequest extends FormRequest
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
            'studentGrade' => 'required|string|max:255',
            'medium' => 'nullable|string|max:255',
            'studentClass' => 'nullable|string|max:255',
            'studentAdmissionNo' => 'nullable|string|max:255',
            'parentNo' => 'nullable|string|max:255',
            'parentProfession' => 'nullable|string|max:255',
        ];
    }
}
