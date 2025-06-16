<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Create User') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Add a new user to the system') }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                <x-icon name="list" class="h-4 w-4 mr-2" />
                {{ __('Back to Users') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form method="POST" action="{{ route('admin.users.store') }}" class="p-6 space-y-6">
            @csrf

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Full Name') }} <span class="text-error-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                           placeholder="Enter user's full name"
                           class="form-input">
                    @error('name')
                        <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Email Address') }} <span class="text-error-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required
                           placeholder="Enter user's email address"
                           class="form-input">
                    @error('email')
                        <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Phone Number') }}
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                           placeholder="Enter user's phone number (optional)"
                           class="form-input">
                    @error('phone')
                        <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="primary_auth_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Primary Auth Method') }} <span class="text-error-500">*</span>
                    </label>
                    <select name="primary_auth_method" id="primary_auth_method" required
                            class="form-input">
                        <option value="email_password" {{ old('primary_auth_method') === 'email_password' ? 'selected' : '' }}>
                            {{ __('Email + Password') }}
                        </option>
                        <option value="phone_password" {{ old('primary_auth_method') === 'phone_password' ? 'selected' : '' }}>
                            {{ __('Phone + Password') }}
                        </option>
                        <option value="email_otp" {{ old('primary_auth_method') === 'email_otp' ? 'selected' : '' }}>
                            {{ __('Email + OTP') }}
                        </option>
                        <option value="phone_otp" {{ old('primary_auth_method') === 'phone_otp' ? 'selected' : '' }}>
                            {{ __('Phone + OTP') }}
                        </option>
                    </select>
                    @error('primary_auth_method')
                        <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Password') }} <span class="text-error-500">*</span>
                    </label>
                    <input type="password" name="password" id="password" required
                           placeholder="Enter a strong password"
                           class="form-input">
                    @error('password')
                        <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Confirm Password') }} <span class="text-error-500">*</span>
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation" required
                           placeholder="Re-enter the password"
                           class="form-input">
                </div>
            </div>

            <!-- Roles -->
            @can('manage_user_permissions')
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    {{ __('Assign Roles') }}
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('Select the roles this user should have') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-start p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                   {{ in_array($role->id, old('roles', [])) ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $role->permissions->count() }} {{ __('permissions') }}</div>
                                @if($role->users->count() > 0)
                                    <div class="text-xs text-gray-400 dark:text-gray-500">{{ $role->users->count() }} {{ __('users assigned') }}</div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                @enderror
            </div>
            @endcan

            <!-- Organizations -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    {{ __('Organization Memberships') }}
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('Select which organizations this user belongs to') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($organizations as $organization)
                        <label class="flex items-start p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200">
                            <input type="checkbox" name="organizations[]" value="{{ $organization->id }}" 
                                   {{ in_array($organization->id, old('organizations', [])) ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-300 text-success-600 shadow-sm focus:border-success-500 focus:ring-success-500">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $organization->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $organization->users->count() }} {{ __('members') }} • {{ $organization->organizationGroups->count() }} {{ __('groups') }}</div>
                                @if($organization->description)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ Str::limit($organization->description, 80) }}</div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('organizations')
                    <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Organization Groups -->
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    {{ __('Group Memberships') }}
                </label>
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-3">{{ __('Select which groups this user should join within organizations') }}</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                    @foreach($organizationGroups->groupBy('organization.name') as $orgName => $groups)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2 flex items-center">
                                <svg class="h-3 w-3 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ $orgName }}
                            </h4>
                            @foreach($groups as $group)
                                <label class="flex items-start p-2 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer mb-2 transition-colors duration-200">
                                    <input type="checkbox" name="organization_groups[]" value="{{ $group->id }}" 
                                           {{ in_array($group->id, old('organization_groups', [])) ? 'checked' : '' }}
                                           class="mt-1 rounded border-gray-300 text-info-600 shadow-sm focus:border-info-500 focus:ring-info-500">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $group->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $group->users->count() }} {{ __('members') }}</div>
                                        @if($group->description)
                                            <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ Str::limit($group->description, 60) }}</div>
                                        @endif
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @error('organization_groups')
                    <p class="mt-1 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <x-icon name="plus" class="h-4 w-4 mr-2" />
                    {{ __('Create User') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
