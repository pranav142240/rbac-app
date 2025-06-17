<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AddAuthMethodRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'auth_method_type' => 'required|in:email_password,email_otp,phone_password,phone_otp,google_sso',
            'identifier' => 'required_unless:auth_method_type,google_sso',
            'password' => 'required_if:auth_method_type,email_password,phone_password|min:8',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'auth_method_type.required' => 'Please select an authentication method.',
            'auth_method_type.in' => 'Invalid authentication method selected.',
            'identifier.required_unless' => 'Please enter your email or phone number.',
            'password.required_if' => 'Password is required for password-based authentication methods.',
            'password.min' => 'Password must be at least 8 characters long.',
        ];
    }
}
