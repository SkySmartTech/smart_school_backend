<?php

namespace App\Http\Requests\UserTypeRegister;

use Illuminate\Foundation\Http\FormRequest;

class UserTeacherRegisterRequest extends FormRequest
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
            'teacherData'           => 'required|array',
            'teacherData.*.teacherGrade'  => 'nullable|string|max:255',
            'teacherData.*.teacherClass'  => 'nullable|string|max:255',
            'teacherData.*.subject'       => 'nullable|string|max:255',
            'teacherData.*.medium'        => 'nullable|string|max:255',
            'teacherData.*.staffNo'       => 'nullable|string|max:255',
            'teacherData.*.userId'       => 'nullable|string|max:255',
            'teacherData.*.userType'       => 'nullable|string|max:255',
        ];
    }
}
