<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
        $authMethod = $this->input('auth_method_type');
        
        $rules = [
            'name' => 'required|string|max:255',
            'auth_method_type' => 'required|in:email_password,email_otp,phone_password,phone_otp,google_sso',
        ];

        // Add email validation only for email-based methods
        if (in_array($authMethod, ['email_password', 'email_otp', 'google_sso'])) {
            $rules['email'] = 'required|email|unique:users';
        }

        // Add phone validation only for phone-based methods
        if (in_array($authMethod, ['phone_password', 'phone_otp'])) {
            $rules['phone'] = 'required|unique:users';
        }

        // Add password validation only for password-based methods
        if (in_array($authMethod, ['email_password', 'phone_password'])) {
            $rules['password'] = 'required|min:8|confirmed';
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your full name.',
            'auth_method_type.required' => 'Please select an authentication method.',
            'auth_method_type.in' => 'Invalid authentication method selected.',
            'email.required' => 'Email address is required for this authentication method.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.required' => 'Phone number is required for this authentication method.',
            'phone.unique' => 'This phone number is already registered.',
            'password.required' => 'Password is required for this authentication method.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        // Set the identifier based on auth method
        $identifier = $this->input('email') ?? $this->input('phone');
        
        if ($identifier) {
            $this->merge([
                'identifier' => $identifier,
            ]);
        }
    }
}
