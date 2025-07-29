<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileUpdateRequest extends FormRequest
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255|unique:users,email',
            'address'   => 'nullable|string|max:255',
            'birthDay'  => 'nullable|date',
            'contact'   => 'nullable|string|max:255',
            'medium'    => 'nullable|string|max:255',
            'gender'    => 'nullable|string|max:255',
            'grade'     => 'nullable|string|max:255',
            'subject'   => 'nullable|string|max:255',
            'class'     => 'nullable|string|max:255',
            'profession'=> 'nullable|string|max:255',
        ];
    }
}
