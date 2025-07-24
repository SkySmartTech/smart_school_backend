<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name'          => 'nullable|string|max:255',
            'email'         => 'nullable|string|email|max:255',
            'address'       => 'nullable|string|max:255',
            'birthDay'      => 'nullable|date',
            'contact'       => 'nullable|string|max:255',
            'medium'        => 'nullable|string|max:255',
            'gender'        => 'nullable|string|max:255',
            'photo'         => 'nullable|string|max:255',
            'userId'        => 'nullable|string|max:255',
            'userType'      => 'nullable|string|max:255',
            'grade'         => 'nullable|string|max:255',
            'subject'       => 'nullable|string|max:255',
            'class'         => 'nullable|string|max:255',
            'profession'    => 'nullable|string|max:255',
            'parentContact' => 'nullable|string|max:255',
            'username'      => 'nullable|string|max:255',
            'userRole'      => 'nullable|string|max:255',
            'location'      => 'nullable|string|max:255',
            'status'        => 'nullable|boolean|max:255',
        ];
    }
}
