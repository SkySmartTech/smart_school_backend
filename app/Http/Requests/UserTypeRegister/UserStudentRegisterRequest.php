<?php

namespace App\Http\Requests\UserTypeRegister;

use Illuminate\Foundation\Http\FormRequest;

class UserStudentRegisterRequest extends FormRequest
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
            'studentGrade' => 'nullable|string|max:255',
            'studentAdmissionNo' => 'nullable|string|max:255',
        ];
    }
}
