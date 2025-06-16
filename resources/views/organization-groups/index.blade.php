<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Organization Groups') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Manage your group memberships') }}</p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <x-icon name="office-building" class="h-4 w-4 mr-2" />
                    {{ __('Organizations') }}
                </a>
            </div>
        </div>
    </div>

    @if($groups->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Your Groups') }}</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Groups you are assigned to across all organizations') }}</p>
            </div>

            <div class="divide-y divide-gray-200 dark:divide-gray-700">
                @foreach($groups as $group)
                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                    <x-icon name="user-group" class="h-6 w-6 text-green-600 dark:text-green-400" />
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $group->name }}
                                    </h3>
                                    <div class="flex items-center space-x-4 mt-1">
                                        <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <x-icon name="office-building" class="h-4 w-4 mr-1" />
                                            {{ $group->organization->name }}
                                        </span>
                                        <span class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400">
                                            <x-icon name="users" class="h-4 w-4 mr-1" />
                                            {{ $group->users_count }} {{ __('members') }}
                                        </span>
                                    </div>
                                    @if($group->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">
                                            {{ Str::limit($group->description, 100) }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('organization-groups.show', $group) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md text-sm leading-4 font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    <x-icon name="eye" class="h-4 w-4 mr-1" />
                                    {{ __('View') }}
                                </a>
                                
                                <a href="{{ route('organizations.show', $group->organization) }}" 
                                   class="inline-flex items-center px-3 py-2 border border-blue-300 dark:border-blue-600 rounded-md text-sm leading-4 font-medium text-blue-700 dark:text-blue-300 bg-blue-50 dark:bg-blue-900/20 hover:bg-blue-100 dark:hover:bg-blue-900/30 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                                    <x-icon name="office-building" class="h-4 w-4 mr-1" />
                                    {{ __('Organization') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Pagination -->
        @if($groups->hasPages())
            <div class="mt-6">
                {{ $groups->links() }}
            </div>
        @endif
    @else
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-12">
            <div class="text-center">
                <x-icon name="user-group" class="h-16 w-16 text-gray-400 mx-auto mb-4" />
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">
                    {{ __('No Groups Found') }}
                </h3>
                <p class="text-gray-600 dark:text-gray-400 mb-6">
                    {{ __('You are not a member of any organization groups yet.') }}
                </p>
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('organizations.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800">
                        <x-icon name="office-building" class="h-4 w-4 mr-2" />
                        {{ __('View Organizations') }}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="user-group" class="h-6 w-6 text-green-600 dark:text-green-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Groups') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $groups->total() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="office-building" class="h-6 w-6 text-blue-600 dark:text-blue-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Organizations') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $groups->groupBy('organization_id')->count() }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center mr-4">
                    <x-icon name="users" class="h-6 w-6 text-purple-600 dark:text-purple-400" />
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Total Members') }}</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ $groups->sum('users_count') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
