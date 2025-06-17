<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class MultiAuthLoginRequest extends FormRequest
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
        $authType = $this->input('auth_type');
        
        $rules = [
            'auth_type' => 'required|in:email_password,phone_password,email_otp,phone_otp',
            'identifier' => 'required',
        ];

        // Add password validation for password-based methods
        if (in_array($authType, ['email_password', 'phone_password'])) {
            $rules['password'] = 'required';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'auth_type.required' => 'Please select an authentication method.',
            'auth_type.in' => 'Invalid authentication method selected.',
            'identifier.required' => 'Please enter your email or phone number.',
            'password.required' => 'Password is required for this authentication method.',
        ];
    }
}
