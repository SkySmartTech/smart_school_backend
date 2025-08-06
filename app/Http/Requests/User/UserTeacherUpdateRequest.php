<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserTeacherUpdateRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'address' => 'nullable|string|max:255',
            'email' => 'required|email',
            'birthDay' => 'nullable|date',
            'contact' => 'nullable|string|max:15',
            'userType' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'username' => 'nullable|string|max:255',
            'photo' => 'nullable|string|max:255',
            'userRole' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',
            'teacherGrades' => 'nullable|array',
            'teacherClass' => 'nullable|array',
            'subjects' => 'nullable|array',
            'staffNo' => 'nullable|string|max:255',
            'medium' => 'nullable|array'
        ];
    }
}
