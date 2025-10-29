<?php

namespace App\Http\Requests\UserTypeProfile;

use Illuminate\Foundation\Http\FormRequest;

class UserParentProfileUpdateRequest extends FormRequest
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
            'studentAdmissionNo' => 'nullable|string|min:5|max:10',
            'profession' => 'nullable|string|max:255',
            'relation' => 'nullable|string|max:255'
        ];
    }
}
