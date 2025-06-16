<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('User Details') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View user information and memberships') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                @can('manage_user_permissions')
                    <!-- Quick Management Dropdown -->
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="btn btn-success">
                            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                            {{ __('Manage') }}
                            <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                            <div class="py-1">
                                <a href="{{ route('admin.users.manage-roles', $user) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                    </svg>
                                    {{ __('Roles & Groups') }}
                                </a>
                                <a href="{{ route('admin.users.manage-organizations', $user) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                    <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    {{ __('Organizations') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endcan
                
                @can('update_users')
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="btn btn-warning">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                        {{ __('Edit User') }}
                    </a>
                @endcan
                
                <a href="{{ route('admin.users.index') }}" 
                   class="btn btn-secondary">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- User Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Info -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Basic Information') }}</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center mb-6">
                        <div class="w-16 h-16 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-4">
                            <span class="text-primary-600 dark:text-primary-400 font-bold text-xl">
                                {{ $user->initials() }}
                            </span>
                        </div>
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                            <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                            @if($user->phone)
                                <p class="text-gray-600 dark:text-gray-400">{{ $user->phone }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Primary Auth Method') }}</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ ucfirst(str_replace('_', ' + ', $user->primary_auth_method)) }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Account Created') }}</label>
                            <p class="text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Email Verified') }}</label>
                            <p class="text-gray-900 dark:text-gray-100">
                                @if($user->email_verified_at)
                                    <span class="text-success-600 dark:text-success-400">✓ {{ $user->email_verified_at->format('M d, Y') }}</span>
                                @else
                                    <span class="text-error-600 dark:text-error-400">✗ Not verified</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone Verified') }}</label>
                            <p class="text-gray-900 dark:text-gray-100">
                                @if($user->phone_verified_at)
                                    <span class="text-success-600 dark:text-success-400">✓ {{ $user->phone_verified_at->format('M d, Y') }}</span>
                                @else
                                    <span class="text-error-600 dark:text-error-400">✗ Not verified</span>
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auth Methods -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Authentication Methods') }}</h3>
                </div>
                <div class="p-6">
                    @if($user->authMethods->count() > 0)
                        <div class="space-y-3">
                            @foreach($user->authMethods as $method)
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                            <x-icon name="key" class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ ucfirst(str_replace('_', ' + ', $method->auth_method_type)) }}
                                            </h4>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">{{ $method->identifier }}</p>
                                        </div>
                                    </div>
                                    <div>
                                        @if($method->is_active && $method->auth_method_verified_at)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200">
                                                ⚠ Inactive
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('No authentication methods configured.') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">            <!-- Roles -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Roles') }}</h3>
                    @can('manage_user_permissions')
                        <a href="{{ route('admin.users.manage-roles', $user) }}" 
                           class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300 text-sm">
                            {{ __('Manage') }}
                        </a>
                    @endcan
                </div>
                <div class="p-6">
                    @if($user->roles->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->roles as $role)
                                <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <x-icon name="shield" class="h-4 w-4 text-gray-600 dark:text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('No roles assigned.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Organizations -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Organizations') }}</h3>
                    @can('manage_user_permissions')
                        <a href="{{ route('admin.users.manage-organizations', $user) }}" 
                           class="text-success-600 hover:text-success-700 dark:text-success-400 dark:hover:text-success-300 text-sm">
                            {{ __('Manage') }}
                        </a>
                    @endcan
                </div>
                <div class="p-6">
                    @if($user->organizations->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->organizations as $organization)
                                <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <x-icon name="office-building" class="h-4 w-4 text-gray-600 dark:text-gray-400 mr-2" />
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $organization->name }}</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $organization->users->count() }} members</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('Not a member of any organizations.') }}</p>
                    @endif
                </div>
            </div>

            <!-- Organization Groups -->
            <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Groups') }}</h3>
                </div>
                <div class="p-6">
                    @if($user->organizationGroups->count() > 0)
                        <div class="space-y-2">
                            @foreach($user->organizationGroups as $group)
                                <div class="flex items-center p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <x-icon name="user-group" class="h-4 w-4 text-gray-600 dark:text-gray-400 mr-2" />
                                    <div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $group->name }}</span>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $group->organization->name ?? 'No Organization' }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-500 dark:text-gray-400 text-center py-4">{{ __('Not a member of any groups.') }}</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
