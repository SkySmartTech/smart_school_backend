<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class UserRegisterRequest extends FormRequest
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
            'userId'    => 'nullable|string|max:255',
            'userRole'  => 'required|in:admin,user',
            'name'      => 'required|string|max:255',
            'username'  => 'required|string|max:255|unique:users',
            'password'  => 'required|string|min:4|confirmed',
            'userType'  => 'nullable|string|max:255',
            'contact'   => 'nullable|string|max:255',
            'birthDay'  => 'nullable|date',
            'address'   => 'nullable|string|max:255',
            'location'  => 'nullable|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'gender'    => 'nullable|string|max:255',
            'photo'     => 'nullable|string|max:255',
            'status'    => 'nullable|boolean',
        ];
    }
}
