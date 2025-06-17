<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100">{{ __('Admin Dashboard')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('System overview and management') }}</p>
    </div>    <!-- Admin Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Users Card -->
        <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg shadow-sm p-6 border border-primary-200 dark:border-primary-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-primary-600 dark:text-primary-400 text-sm font-medium">{{ __('Total Users') }}</p>
                    <p class="text-3xl font-bold text-primary-900 dark:text-primary-100 mt-1">{{ $stats['total_users'] }}</p>
                    <p class="text-xs text-primary-600 dark:text-primary-400 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        {{ __('Registered') }}
                    </p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- New Users This Month Card -->
        <div class="bg-success-50 dark:bg-success-900/20 rounded-lg shadow-sm p-6 border border-success-200 dark:border-success-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-success-600 dark:text-success-400 text-sm font-medium">{{ __('New This Month') }}</p>
                    <p class="text-3xl font-bold text-success-900 dark:text-success-100 mt-1">{{ $stats['new_users_this_month'] }}</p>
                    <p class="text-xs text-success-600 dark:text-success-400 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                        {{ __('Growth') }}
                    </p>
                </div>
                <div class="bg-success-100 dark:bg-success-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-success-600 dark:text-success-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Active Users This Week Card -->
        <div class="bg-info-50 dark:bg-info-900/20 rounded-lg shadow-sm p-6 border border-info-200 dark:border-info-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-info-600 dark:text-info-400 text-sm font-medium">{{ __('Active This Week') }}</p>
                    <p class="text-3xl font-bold text-info-900 dark:text-info-100 mt-1">{{ $stats['active_users_this_week'] }}</p>
                    <p class="text-xs text-info-600 dark:text-info-400 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.636 18.364a9 9 0 010-12.728m12.728 0a9 9 0 010 12.728m-9.9-2.829a5 5 0 010-7.07m7.072 0a5 5 0 010 7.07M13 12a1 1 0 11-2 0 1 1 0 012 0z" />
                        </svg>
                        {{ __('Online') }}
                    </p>
                </div>
                <div class="bg-info-100 dark:bg-info-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-info-600 dark:text-info-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- System Stats Card -->
        <div class="bg-warning-50 dark:bg-warning-900/20 rounded-lg shadow-sm p-6 border border-warning-200 dark:border-warning-800">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-warning-600 dark:text-warning-400 text-sm font-medium">{{ __('System') }}</p>
                    <p class="text-lg font-bold text-warning-900 dark:text-warning-100 mt-1">{{ $systemActivity['total_roles'] }} {{ __('Roles') }}</p>
                    <p class="text-xs text-warning-600 dark:text-warning-400 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        {{ $systemActivity['total_permissions'] }} {{ __('Permissions') }}
                    </p>
                </div>
                <div class="bg-warning-100 dark:bg-warning-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-warning-600 dark:text-warning-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Admin Actions & Management Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Quick Admin Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">{{ __('Quick Actions') }}</h2>
                <div class="grid grid-cols-2 gap-4">                    @can('create_users')
                    <a href="{{ route('admin.users.create') }}" class="flex items-center p-4 bg-primary-50 dark:bg-primary-900/20 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/30 transition-colors border border-primary-200 dark:border-primary-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-600 dark:text-primary-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        <span class="text-primary-700 dark:text-primary-300 font-medium">{{ __('Add User') }}</span>
                    </a>
                    @endcan

                    @can('create_roles')
                    <a href="{{ route('roles.create') }}" class="flex items-center p-4 bg-success-50 dark:bg-success-900/20 rounded-lg hover:bg-success-100 dark:hover:bg-success-900/30 transition-colors border border-success-200 dark:border-success-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success-600 dark:text-success-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <span class="text-success-700 dark:text-success-300 font-medium">{{ __('Add Role') }}</span>
                    </a>
                    @endcan

                    @can('view_users')
                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 bg-info-50 dark:bg-info-900/20 rounded-lg hover:bg-info-100 dark:hover:bg-info-900/30 transition-colors border border-info-200 dark:border-info-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-600 dark:text-info-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span class="text-info-700 dark:text-info-300 font-medium">{{ __('Manage Users') }}</span>
                    </a>
                    @endcan

                    @can('view_roles')
                    <a href="{{ route('roles.index') }}" class="flex items-center p-4 bg-warning-50 dark:bg-warning-900/20 rounded-lg hover:bg-warning-100 dark:hover:bg-warning-900/30 transition-colors border border-warning-200 dark:border-warning-800">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-warning-600 dark:text-warning-400 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-warning-700 dark:text-warning-300 font-medium">{{ __('Manage Roles') }}</span>
                    </a>
                    @endcan
                </div>
            </div>
        </div>

        <!-- System Status -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">{{ __('System Status') }}</h2>
                <div class="space-y-4">                    <div class="flex items-center justify-between p-3 bg-success-50 dark:bg-success-900/20 rounded-lg border border-success-200 dark:border-success-800">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-success-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-success-700 dark:text-success-300">{{ __('Application Status') }}</span>
                        </div>
                        <span class="text-sm text-success-600 dark:text-success-400 font-medium">{{ __('Online') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-success-50 dark:bg-success-900/20 rounded-lg border border-success-200 dark:border-success-800">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-success-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-success-700 dark:text-success-300">{{ __('Database') }}</span>
                        </div>
                        <span class="text-sm text-success-600 dark:text-success-400 font-medium">{{ __('Connected') }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800">
                        <div class="flex items-center">
                            <div class="w-2 h-2 bg-primary-500 rounded-full mr-3"></div>
                            <span class="text-sm font-medium text-primary-700 dark:text-primary-300">{{ __('RBAC System') }}</span>
                        </div>
                        <span class="text-sm text-primary-600 dark:text-primary-400 font-medium">{{ __('Active') }}</span>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                        {{ __('Last updated') }}: {{ now()->format('Y-m-d H:i:s') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('Recent Users') }}</h2>
                @can('view_users')
                <a href="{{ route('admin.users.index') }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm font-medium">
                    {{ __('View All Users') }}
                </a>
                @endcan
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('User') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Email') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Role') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Joined') }}</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($recentUsers as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center">
                                        <span class="text-primary-600 dark:text-primary-400 font-medium text-sm">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->email ?? $user->phone }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->roles->count() > 0)
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                        {{ $user->roles->first()->name }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-full">
                                        {{ __('No Role') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                @can('view_users')
                                <a href="{{ route('admin.users.show', $user) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                    {{ __('View') }}
                                </a>
                                @endcan
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Admin Info Panel -->
    <div class="bg-gradient-to-r from-indigo-50 to-blue-50 dark:from-indigo-900/20 dark:to-blue-900/20 rounded-lg shadow-sm border border-indigo-200 dark:border-indigo-800 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center">
                <div class="bg-indigo-100 dark:bg-indigo-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                    </svg>
                </div>
                <div class="ml-4">
                    <h2 class="text-lg font-medium text-indigo-900 dark:text-indigo-100">{{ __('Administrator Panel') }}</h2>
                    <p class="text-indigo-800 dark:text-indigo-200 text-sm">
                        {{ __('Welcome,') }} <strong>{{ Auth::user()->name }}</strong>. {{ __('You have full system access and management capabilities.') }}
                    </p>
                    <p class="text-xs text-indigo-700 dark:text-indigo-300 mt-1">
                        {{ __('Last login') }}: {{ $systemActivity['last_login']->format('M d, Y \a\t H:i') }}
                    </p>
                </div>
            </div>
        </div>
    </div>

</x-layouts.app>
