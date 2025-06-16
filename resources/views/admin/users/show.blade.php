<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('User Details') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('View user information and memberships') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                @can('update_users')
                    <a href="{{ route('admin.users.edit', $user) }}" 
                       class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:bg-yellow-700 active:bg-yellow-900 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <x-icon name="settings" class="h-4 w-4 mr-2" />
                        {{ __('Edit User') }}
                    </a>
                @endcan
                <a href="{{ route('admin.users.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-icon name="list" class="h-4 w-4 mr-2" />
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
                        <div class="w-16 h-16 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-4">
                            <span class="text-blue-600 dark:text-blue-400 font-bold text-xl">
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
                                    <span class="text-green-600 dark:text-green-400">✓ {{ $user->email_verified_at->format('M d, Y') }}</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">✗ Not verified</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <label class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Phone Verified') }}</label>
                            <p class="text-gray-900 dark:text-gray-100">
                                @if($user->phone_verified_at)
                                    <span class="text-green-600 dark:text-green-400">✓ {{ $user->phone_verified_at->format('M d, Y') }}</span>
                                @else
                                    <span class="text-red-600 dark:text-red-400">✗ Not verified</span>
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
                                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                            <x-icon name="key" class="h-5 w-5 text-blue-600 dark:text-blue-400" />
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
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                ✓ Active
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
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
                           class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm">
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
                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300 text-sm">
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
