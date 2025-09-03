<?php

namespace App\Http\Requests\UserTypeRegister;

use Illuminate\Foundation\Http\FormRequest;

class StudentMultiCreateRequest extends FormRequest
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
            'studentData'                       => 'required|array',
            'studentData.*.name'                => 'required|string|max:255',
            'studentData.*.address'             => 'nullable|string|max:255',
            'studentData.*.email'               => 'required|email|unique:users,email|max:255',
            'studentData.*.birthDay'            => 'nullable|date',
            'studentData.*.contact'             => 'nullable|string|max:15',
            'studentData.*.userType'            => 'required|string|max:255',
            'studentData.*.gender'              => 'nullable|string|max:255',
            'studentData.*.location'            => 'nullable|string|max:255',
            'studentData.*.username'            => 'required|string|max:255|unique:users,username',
            'studentData.*.password'            => 'required|string|min:8',
            'studentData.*.photo'               => 'nullable|string|max:255',
            'studentData.*.userRole'            => 'nullable|string|max:255',
            'studentData.*.status'              => 'nullable|boolean',
            'studentData.*.studentGrade'        => 'nullable|string|max:255',
            'studentData.*.studentClass'        => 'nullable|string|max:255',
            'studentData.*.medium'              => 'nullable|string|max:255',
            'studentData.*.studentAdmissionNo'  => 'nullable|string|max:255',
        ];
    }
}
