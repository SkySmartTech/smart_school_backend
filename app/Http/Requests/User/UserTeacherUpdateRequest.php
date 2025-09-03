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
            'email' => 'required|email|max:255',
            'birthDay' => 'nullable|date',
            'contact' => 'nullable|string|max:15',
            'userType' => 'required|string|max:255',
            'gender' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'username' => 'required|string|max:255',
            'photo' => 'nullable|string|max:255',
            'userRole' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',

            'teacherData'           => 'required|array',
            'teacherData.*.teacherGrade'  => 'nullable|string|max:255',
            'teacherData.*.teacherClass'  => 'nullable|string|max:255',
            'teacherData.*.subject'       => 'nullable|string|max:255',
            'teacherData.*.medium'        => 'nullable|string|max:255',
            'teacherData.*.staffNo'       => 'nullable|string|max:255',
        ];
    }
}
