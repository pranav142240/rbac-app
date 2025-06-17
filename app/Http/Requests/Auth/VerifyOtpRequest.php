<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class VerifyOtpRequest extends FormRequest
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
            'identifier' => 'required',
            'otp' => 'required|string|size:6',
            'type' => 'required|in:email,phone',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'identifier.required' => 'Please enter your email or phone number.',
            'otp.required' => 'Please enter the OTP code.',
            'otp.size' => 'OTP must be exactly 6 digits.',
            'type.required' => 'Authentication type is required.',
            'type.in' => 'Invalid authentication type.',
        ];
    }
}
