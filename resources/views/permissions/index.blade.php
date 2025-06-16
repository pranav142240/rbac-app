<x-layouts.app>    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Permission Management') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage system permissions organized by category') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Permission Categories -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                @foreach ($categorizedPermissions as $categoryName => $category)
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 border border-gray-200 dark:border-gray-600">
                        <!-- Category Header -->
                        <div class="flex items-center mb-4">
                            <div class="flex-shrink-0">
                                @switch($category['icon'])
                                    @case('users')
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        @break
                                    @case('user-circle')
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @break                                    @case('shield-check')
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        @break
                                    @case('lock-closed')
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        @break
                                    @case('document-text')
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        @break
                                    @case('pencil-alt')
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-6 h-6 text-{{ $category['color'] }}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-3">
                                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ $categoryName }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ count($category['permissions']) }} permissions</p>
                            </div>
                        </div>

                        <!-- Permissions List -->
                        <div class="space-y-3">
                            @foreach ($category['permissions'] as $permission)
                                <div class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-750 transition-colors">
                                    <div class="flex-1">
                                        <div class="flex items-center">
                                            <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $permission->name }}
                                            </h4>
                                            @if ($permission->guard_name !== 'web')
                                                <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                                    {{ $permission->guard_name }}
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <!-- Roles using this permission -->
                                        @if ($permission->roles->count() > 0)
                                            <div class="mt-2 flex flex-wrap gap-1">
                                                @foreach ($permission->roles->take(3) as $role)
                                                    <span class="px-2 py-1 text-xs bg-{{ $category['color'] }}-100 text-{{ $category['color'] }}-800 rounded-full dark:bg-{{ $category['color'] }}-900 dark:text-{{ $category['color'] }}-200">
                                                        {{ $role->name }}
                                                    </span>
                                                @endforeach
                                                @if ($permission->roles->count() > 3)
                                                    <span class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded-full dark:bg-gray-700 dark:text-gray-300">
                                                        +{{ $permission->roles->count() - 3 }} more
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">No roles assigned</p>
                                        @endif
                                    </div>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-2 ml-4">
                                        @can('view_permissions')
                                        <a href="{{ route('permissions.show', $permission) }}" 
                                           class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 text-sm">
                                            View
                                        </a>
                                        @endcan

                                        @can('edit_permissions')
                                        <a href="{{ route('permissions.edit', $permission) }}" 
                                           class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300 text-sm">
                                            Edit
                                        </a>
                                        @endcan

                                        @can('delete_permissions')
                                        <form action="{{ route('permissions.destroy', $permission) }}" 
                                              method="POST" 
                                              class="inline" 
                                              onsubmit="return confirm('Are you sure you want to delete this permission?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 text-sm">
                                                Delete
                                            </button>
                                        </form>
                                        @endcan
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>            @if (empty($categorizedPermissions))
                <div class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('No permissions found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('No permissions are currently available in the system.') }}</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
