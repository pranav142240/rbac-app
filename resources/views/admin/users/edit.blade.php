<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit User') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Update user information for') }} <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-icon name="eye" class="h-4 w-4 mr-2" />
                    {{ __('View User') }}
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-icon name="list" class="h-4 w-4 mr-2" />
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <form method="POST" action="{{ route('admin.users.update', $user) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Information -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Full Name') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Email Address') }} <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Phone Number') }}
                    </label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="primary_auth_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Primary Auth Method') }} <span class="text-red-500">*</span>
                    </label>
                    <select name="primary_auth_method" id="primary_auth_method" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="email_password" {{ old('primary_auth_method', $user->primary_auth_method) === 'email_password' ? 'selected' : '' }}>
                            {{ __('Email + Password') }}
                        </option>
                        <option value="phone_password" {{ old('primary_auth_method', $user->primary_auth_method) === 'phone_password' ? 'selected' : '' }}>
                            {{ __('Phone + Password') }}
                        </option>
                        <option value="email_otp" {{ old('primary_auth_method', $user->primary_auth_method) === 'email_otp' ? 'selected' : '' }}>
                            {{ __('Email + OTP') }}
                        </option>
                        <option value="phone_otp" {{ old('primary_auth_method', $user->primary_auth_method) === 'phone_otp' ? 'selected' : '' }}>
                            {{ __('Phone + OTP') }}
                        </option>
                    </select>
                    @error('primary_auth_method')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Password (Optional) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('New Password') }}
                        <span class="text-sm text-gray-500">({{ __('leave blank to keep current') }})</span>
                    </label>
                    <input type="password" name="password" id="password"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        {{ __('Confirm New Password') }}
                    </label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                           class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <!-- Roles -->
            @can('manage_user_permissions')
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Assign Roles') }}
                </label>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    @foreach($roles as $role)
                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ $user->hasRole($role->name) ? 'bg-blue-50 dark:bg-blue-900/20 border-blue-300 dark:border-blue-600' : '' }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $role->permissions->count() }} permissions</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
            @endcan

            <!-- Organizations -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Organization Memberships') }}
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                    @foreach($organizations as $organization)
                        <label class="flex items-center p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer {{ $user->belongsToOrganization($organization) ? 'bg-green-50 dark:bg-green-900/20 border-green-300 dark:border-green-600' : '' }}">
                            <input type="checkbox" name="organizations[]" value="{{ $organization->id }}" 
                                   {{ $user->belongsToOrganization($organization) ? 'checked' : '' }}
                                   class="rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring-green-500">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $organization->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $organization->users->count() }} members</div>
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('organizations')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Organization Groups -->
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                    {{ __('Group Memberships') }}
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-64 overflow-y-auto">
                    @foreach($organizationGroups->groupBy('organization.name') as $orgName => $groups)
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">{{ $orgName }}</h4>
                            @foreach($groups as $group)
                                <label class="flex items-center p-2 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer mb-2 {{ $user->belongsToOrganizationGroup($group) ? 'bg-purple-50 dark:bg-purple-900/20 border-purple-300 dark:border-purple-600' : '' }}">
                                    <input type="checkbox" name="organization_groups[]" value="{{ $group->id }}" 
                                           {{ $user->belongsToOrganizationGroup($group) ? 'checked' : '' }}
                                           class="rounded border-gray-300 text-purple-600 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $group->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $group->users->count() }} members</div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    @endforeach
                </div>
                @error('organization_groups')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 focus:bg-gray-400 active:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Cancel') }}
                </a>
                <button type="submit" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-icon name="settings" class="h-4 w-4 mr-2" />
                    {{ __('Update User') }}
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
