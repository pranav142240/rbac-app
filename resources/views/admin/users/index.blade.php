<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('User Management') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage users, roles, and organization memberships') }}</p>
            </div>
            @can('create_users')
                <a href="{{ route('admin.users.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    <x-icon name="plus" class="h-4 w-4 mr-2" />
                    {{ __('Create User') }}
                </a>
            @endcan
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Search') }}</label>
                <input type="text" name="search" id="search" value="{{ request('search') }}" 
                       placeholder="{{ __('Name, email, or phone...') }}"
                       class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
            
            <div>
                <label for="role" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Role') }}</label>
                <select name="role" id="role" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('All Roles') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label for="organization" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('Organization') }}</label>
                <select name="organization" id="organization" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">{{ __('All Organizations') }}</option>
                    @foreach($organizations as $org)
                        <option value="{{ $org->id }}" {{ request('organization') == $org->id ? 'selected' : '' }}>
                            {{ $org->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    {{ __('Filter') }}
                </button>
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
                                    <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-3">
                                        <span class="text-blue-600 dark:text-blue-400 font-semibold text-sm">
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
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                                   {{ $role->name === 'Admin' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : 
                                                      ($role->name === 'Organization Manager' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 
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
                                       class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        <x-icon name="eye" class="h-4 w-4" />
                                    </a>
                                    @can('update_users')
                                        <a href="{{ route('admin.users.edit', $user) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">
                                            <x-icon name="settings" class="h-4 w-4" />
                                        </a>
                                    @endcan
                                    @can('manage_user_permissions')
                                        <a href="{{ route('admin.users.manage-roles', $user) }}" 
                                           class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <x-icon name="shield" class="h-4 w-4" />
                                        </a>
                                    @endcan
                                    @can('delete_users')
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        onclick="return confirm('{{ __('Are you sure you want to delete this user?') }}')"
                                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                    <x-icon name="circle" class="h-4 w-4" />
                                                </button>
                                            </form>
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
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="users" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Users') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $users->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="shield" class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Admin Users') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ \App\Models\User::role('Admin')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="office-building" class="h-6 w-6 text-purple-600 dark:text-purple-400" />
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
