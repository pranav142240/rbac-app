<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->can('update_users');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = $this->route('user');
        
        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'primary_auth_method' => 'required|in:email_password,phone_password,email_otp,phone_otp',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'organizations' => 'array',
            'organizations.*' => 'exists:organizations,id',
            'organization_groups' => 'array',
            'organization_groups.*' => 'exists:organization_groups,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The user name is required.',
            'email.required' => 'The email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address is already taken.',
            'phone.unique' => 'This phone number is already taken.',
            'password.min' => 'The password must be at least 8 characters.',
            'password.confirmed' => 'The password confirmation does not match.',
            'primary_auth_method.required' => 'Please select a primary authentication method.',
            'primary_auth_method.in' => 'The selected authentication method is invalid.',
            'roles.array' => 'Roles must be an array.',
            'roles.*.exists' => 'One or more selected roles are invalid.',
            'organizations.array' => 'Organizations must be an array.',
            'organizations.*.exists' => 'One or more selected organizations are invalid.',
            'organization_groups.array' => 'Organization groups must be an array.',
            'organization_groups.*.exists' => 'One or more selected organization groups are invalid.',
        ];
    }
}
