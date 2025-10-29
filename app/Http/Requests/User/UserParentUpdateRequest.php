<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserParentUpdateRequest extends FormRequest
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
            'contact' => 'nullable|string|min:10|max:10',
            'userType' => 'nullable|string|max:255',
            'gender' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'username' => 'required|string|max:255',
            'photo' => 'nullable|string|max:255',
            'userRole' => 'nullable|string|max:255',
            'status' => 'nullable|boolean',

            'parentData'           => 'required|array',
            'parentData.*.studentAdmissionNo'  => 'nullable|string|min:5|max:10',
            'parentData.*.parentContact'  => 'nullable|string|min:10|max:10',
            'parentData.*.profession'       => 'nullable|string|max:255',
            'parentData.*.relation'        => 'nullable|string|max:255',
        ];
    }
}
