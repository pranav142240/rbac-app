<x-layouts.app>

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Dashboard')}}</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Welcome to your dashboard') }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Users Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Welcome') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ Auth::user()->name }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ __('Logged in') }}
                    </p>
                </div>
                <div class="bg-primary-100 dark:bg-primary-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-primary-500 dark:text-primary-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Auth Method Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Auth Method') }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1">{{ ucfirst(str_replace('_', ' + ', Auth::user()->primary_auth_method)) }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        {{ __('Primary') }}
                    </p>
                </div>
                <div class="bg-success-100 dark:bg-success-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-success-500 dark:text-success-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Auth Methods Count -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Auth Methods') }}</p>
                    <p class="text-2xl font-bold text-gray-800 dark:text-gray-100 mt-1">{{ Auth::user()->authMethods->count() }}</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        {{ __('Configured') }}
                    </p>
                </div>
                <div class="bg-info-100 dark:bg-info-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-info-600 dark:text-info-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                </div>
            </div>
        </div>

        <!-- Quick Action -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 border border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Quick Action') }}</p>
                    <p class="text-lg font-bold text-gray-800 dark:text-gray-100 mt-1">Profile</p>
                    <p class="text-xs text-gray-500 flex items-center mt-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <a href="{{ route('profile.edit') }}" class="text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">{{ __('Manage') }}</a>
                    </p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 p-3 rounded-full">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-500 dark:text-orange-300"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
        </div>    </div>

    <!-- Organizations & Groups Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- User Organizations -->
        @can('viewAny', App\Models\Organization::class)
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('My Organizations') }}</h2>
                    @can('create', App\Models\Organization::class)
                        <a href="{{ route('organizations.create') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                            <x-icon name="plus" class="h-4 w-4 mr-1" />
                            {{ __('Create') }}
                        </a>
                    @endcan
                </div>
                
                @if(Auth::user()->organizations->count() > 0)
                    <div class="space-y-3">
                        @foreach(Auth::user()->organizations->take(3) as $organization)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                        <x-icon name="office-building" class="h-5 w-5 text-primary-600 dark:text-primary-400" />
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $organization->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $organization->users()->count() }} {{ __('members') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    @can('view', $organization)
                                        <a href="{{ route('organizations.show', $organization) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                            <x-icon name="eye" class="h-4 w-4" />
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(Auth::user()->organizations->count() > 3)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            @can('view_organizations')
                            <a href="{{ route('organizations.index') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                <x-icon name="collection" class="h-4 w-4 mr-1" />
                                {{ __('View All') }} ({{ Auth::user()->organizations->count() }})
                            </a>
                            @endcan
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <x-icon name="office-building" class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('You are not a member of any organizations yet.') }}</p>
                        @can('create', App\Models\Organization::class)
                            <a href="{{ route('organizations.create') }}" class="mt-2 inline-flex items-center text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                {{ __('Create your first organization') }}
                            </a>
                        @endcan
                    </div>
                @endif
            </div>
        </div>
        @endcan

        <!-- User Groups -->
        @can('view_organization_groups')
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="p-6">                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200">{{ __('My Groups') }}</h2>
                    <a href="{{ route('organization-groups.index') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                        <x-icon name="user-group" class="h-4 w-4 mr-1" />
                        {{ __('Manage') }}
                    </a>
                </div>
                
                @if(Auth::user()->organizationGroups->count() > 0)
                    <div class="space-y-3">
                        @foreach(Auth::user()->organizationGroups->take(3) as $group)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-10 h-10 bg-success-100 dark:bg-success-900 rounded-full flex items-center justify-center mr-3">
                                        <x-icon name="user-group" class="h-5 w-5 text-success-600 dark:text-success-400" />
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $group->name }}
                                        </h3>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $group->organization->name ?? 'No Organization' }}
                                        </p>
                                    </div>
                                </div>                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('organization-groups.show', $group) }}" class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                                        <x-icon name="eye" class="h-4 w-4" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                    @if(Auth::user()->organizationGroups->count() > 3)
                        <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                {{ __('and') }} {{ Auth::user()->organizationGroups->count() - 3 }} {{ __('more groups...') }}
                            </p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-8">
                        <x-icon name="user-group" class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                        <p class="text-gray-500 dark:text-gray-400 text-sm">{{ __('You are not a member of any groups yet.') }}</p>
                    </div>
                @endif
            </div>
        </div>
        @endcan
    </div>

    <!-- Authentication Methods Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden mb-6">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-800 dark:text-gray-200 mb-4">{{ __('Your Authentication Methods') }}</h2>
            <div class="space-y-3">
                @foreach(Auth::user()->authMethods as $method)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <div class="flex items-center">
                            <div class="w-10 h-10 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-3">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ ucfirst(str_replace('_', ' + ', $method->auth_method_type)) }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $method->isVerified() ? 'Verified' : 'Unverified' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            @if($method->isVerified())
                                <span class="status-badge status-badge-success">
                                    ✓ Verified
                                </span>
                            @else
                                <span class="status-badge status-badge-warning">
                                    ⚠ Unverified
                                </span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('auth.profile') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    {{ __('Manage Authentication Methods') }}
                </a>
            </div>
        </div>
    </div>

    <!-- Multi-Auth System Info -->
    <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg shadow-sm border border-primary-200 dark:border-primary-800 overflow-hidden">
        <div class="p-6">
            <h2 class="text-lg font-medium text-primary-900 dark:text-primary-100 mb-2">{{ __('Multi-Authentication System') }}</h2>
            <p class="text-primary-800 dark:text-primary-200 text-sm mb-4">
                {{ __('This application supports multiple authentication methods including Email/Phone with Password or OTP, SSO, and passwordless login via magic links.') }}
            </p>
            <div class="text-xs text-primary-700 dark:text-primary-300">
                <strong>{{ __('Available Auth Methods:') }}</strong> {{ __('Email + Password, Phone + Password, Email + OTP, Phone + OTP, Google SSO, Magic Link') }}
            </div>
        </div>
    </div>

</x-layouts.app>
