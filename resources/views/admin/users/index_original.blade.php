<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('User Management') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage users, roles, and organization memberships') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <!-- Quick Actions Dropdown -->
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open" class="btn btn-secondary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4" />
                        </svg>
                        {{ __('Quick Actions') }}
                        <svg class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                        <div class="py-1">
                            <a href="{{ route('roles.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                                {{ __('Manage Roles') }}
                            </a>
                            <a href="{{ route('permissions.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                {{ __('Manage Permissions') }}
                            </a>
                            <a href="{{ route('organizations.index') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <svg class="h-4 w-4 mr-2 inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                {{ __('Manage Organizations') }}
                            </a>
                        </div>
                    </div>
                </div>
                
                @can('create_users')
                    <a href="{{ route('admin.users.create') }}" 
                       class="btn btn-primary">
                        <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        {{ __('Create User') }}
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <!-- Enhanced Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Filter Users') }}</h3>
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('Total:') }}</span>
                <span class="text-sm font-semibold text-primary-600 dark:text-primary-400">{{ $users->total() }} {{ __('users') }}</span>
            </div>
        </div>
        
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-5 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Search') }}</label>
                <div class="relative">
                    <input type="text" name="search" id="search" value="{{ request('search') }}" 
                           placeholder="{{ __('Name, email, or phone...') }}"
                           class="form-input pl-10">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                </div>
            </div>
            
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Role') }}</label>
                <select name="role" id="role" class="form-input">
                    <option value="">{{ __('All Roles') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }} ({{ $role->users->count() }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="organization" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Organization') }}</label>
                <select name="organization" id="organization" class="form-input">
                    <option value="">{{ __('All Organizations') }}</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ request('organization') == $org->id ? 'selected' : '' }}>
                            {{ $org->name }} ({{ $org->users->count() }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Status') }}</label>
                <select name="status" id="status" class="form-input">
                    <option value="">{{ __('All Status') }}</option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>{{ __('Email Verified') }}</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>{{ __('Email Unverified') }}</option>
                    <option value="phone_verified" {{ request('status') === 'phone_verified' ? 'selected' : '' }}>{{ __('Phone Verified') }}</option>
                </select>
            </div>
            
            <div class="flex items-end space-x-2">
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.207A1 1 0 013 6.5V4z" />
                    </svg>
                    {{ __('Filter') }}
                </button>
                @if(request()->filled(['search', 'role', 'organization', 'status']))
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('User') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Contact') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Roles') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Organizations') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Groups') }}
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            {{ __('Actions') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-primary-600 dark:text-primary-400 font-semibold text-sm">
                                            {{ $user->initials() }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            {{ ucfirst(str_replace('_', ' ', $user->primary_auth_method)) }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->email }}</div>
                                @if($user->phone)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex flex-wrap gap-1">
                                    @forelse($user->roles as $role)
                                        <span class="status-badge 
                                                   {{ $role->name === 'Admin' ? 'status-danger' : 
                                                      ($role->name === 'Organization Manager' ? 'status-primary' : 
                                                       'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300') }}">
                                            {{ $role->name }}
                                        </span>
                                    @empty
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ __('No roles assigned') }}</span>
                                    @endforelse
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->organizations->count() }} {{ __('organizations') }}
                                </div>
                                @if($user->organizations->count() > 0)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->organizations->take(2)->pluck('name')->join(', ') }}
                                        @if($user->organizations->count() > 2)
                                            {{ __('and') }} {{ $user->organizations->count() - 2 }} {{ __('more...') }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-gray-100">
                                    {{ $user->organizationGroups->count() }} {{ __('groups') }}
                                </div>
                                @if($user->organizationGroups->count() > 0)
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $user->organizationGroups->take(2)->pluck('name')->join(', ') }}
                                        @if($user->organizationGroups->count() > 2)
                                            {{ __('and') }} {{ $user->organizationGroups->count() - 2 }} {{ __('more...') }}
                                        @endif
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.show', $user) }}" 
                                       class="btn-action btn-primary"
                                       title="{{ __('View User Details') }}">
                                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </a>
                                    
                                    @can('update_users')
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="btn-action btn-warning"
                                           title="{{ __('Edit User') }}">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </a>
                                    @endcan
                                    
                                    @can('manage_user_permissions')
                                        <a href="{{ route('admin.users.manage-roles', $user) }}" 
                                           class="btn-action btn-success"
                                           title="{{ __('Manage Roles & Permissions') }}">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </a>
                                        
                                        <a href="{{ route('admin.users.manage-organizations', $user) }}" 
                                           class="btn-action btn-info"
                                           title="{{ __('Manage Organizations') }}">
                                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </a>
                                    @endcan
                                    
                                    @can('delete_users')
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('{{ __('Are you sure you want to delete this user? This action cannot be undone.') }}')"
                                                        class="btn-action btn-danger"
                                                        title="{{ __('Delete User') }}">
                                                    <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        @else
                                            <span class="btn-action btn-secondary opacity-50 cursor-not-allowed" title="{{ __('Cannot delete your own account') }}">
                                                <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                                                </svg>
                                            </span>
                                        @endif
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                {{ __('No users found.') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="users" class="h-6 w-6 text-primary-600 dark:text-primary-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $users->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-success-100 dark:bg-success-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="shield" class="h-6 w-6 text-success-600 dark:text-success-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Admin Users') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\User::role('Admin')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-info-100 dark:bg-info-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="office-building" class="h-6 w-6 text-info-600 dark:text-info-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Org Members') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\User::whereHas('organizations')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="user-group" class="h-6 w-6 text-orange-600 dark:text-orange-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Group Members') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\User::whereHas('organizationGroups')->count() }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
