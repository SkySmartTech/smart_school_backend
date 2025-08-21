<?php

namespace App\Http\Requests\UserAccess;

use Illuminate\Foundation\Http\FormRequest;

class UserAccessCreateRequest extends FormRequest
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
            'userType'          => 'required|string',
            'description'       => 'nullable|string',
            'permissionObject'  => 'nullable|array',
        ];
    }
}
