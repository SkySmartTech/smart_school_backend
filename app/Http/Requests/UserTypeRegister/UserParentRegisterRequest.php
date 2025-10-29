<?php

namespace App\Http\Requests\UserTypeRegister;

use Illuminate\Foundation\Http\FormRequest;

class UserParentRegisterRequest extends FormRequest
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
            'parentData'           => 'required|array',
            'parentData.*.studentAdmissionNo'  => 'nullable|string|min:5|max:8',
            'parentData.*.parentContact'  => 'nullable|string|min:10|max:10',
            'parentData.*.profession'       => 'nullable|string|max:255',
            'parentData.*.relation'        => 'nullable|string|max:255',
            'parentData.*.userId'       => 'nullable|string|max:255',
            'parentData.*.userType'       => 'nullable|string|max:255',
        ];
    }
}
