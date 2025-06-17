<?php

namespace App\Http\Requests\Organization;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrganizationRequest extends FormRequest
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
            'name' => 'required|string|max:255|unique:organizations,name',
            'code' => 'required|string|max:10|unique:organizations,code',
            'description' => 'nullable|string|max:1000',
            'organization_group_id' => 'required|exists:organization_groups,id',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The organization name is required.',
            'name.unique' => 'An organization with this name already exists.',
            'code.required' => 'The organization code is required.',
            'code.unique' => 'An organization with this code already exists.',
            'code.max' => 'The organization code must not exceed 10 characters.',
            'organization_group_id.required' => 'Please select an organization group.',
            'organization_group_id.exists' => 'The selected organization group is invalid.',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'is_active' => $this->boolean('is_active', true),
        ]);
    }
}
