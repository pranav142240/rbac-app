<x-layouts.app>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Organizations') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Manage your organizations and memberships') }}</p>
            </div>
            @can('create', App\Models\Organization::class)
                <a href="{{ route('organizations.create') }}" class="btn btn-primary">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Create Organization
                </a>
            @endcan
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if(session('success'))
                <div class="mb-6 alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($organizations->count() > 0)
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg p-4 border border-primary-200 dark:border-primary-800">
                        <div class="flex items-center">
                            <div class="p-2 bg-primary-100 dark:bg-primary-900 rounded-lg">
                                <svg class="h-6 w-6 text-primary-600 dark:text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-primary-600 dark:text-primary-400">Total Organizations</p>
                                <p class="text-2xl font-bold text-primary-900 dark:text-primary-100">{{ $organizations->count() }}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-success-50 dark:bg-success-900/20 rounded-lg p-4 border border-success-200 dark:border-success-800">
                        <div class="flex items-center">
                            <div class="p-2 bg-success-100 dark:bg-success-900 rounded-lg">
                                <svg class="h-6 w-6 text-success-600 dark:text-success-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-success-600 dark:text-success-400">Active</p>
                                <p class="text-2xl font-bold text-success-900 dark:text-success-100">{{ $organizations->where('is_active', true)->count() }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-info-50 dark:bg-info-900/20 rounded-lg p-4 border border-info-200 dark:border-info-800">
                        <div class="flex items-center">
                            <div class="p-2 bg-info-100 dark:bg-info-900 rounded-lg">
                                <svg class="h-6 w-6 text-info-600 dark:text-info-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-info-600 dark:text-info-400">Total Members</p>
                                <p class="text-2xl font-bold text-info-900 dark:text-info-100">{{ $organizations->sum(function($org) { return $org->users->count(); }) }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="bg-warning-50 dark:bg-warning-900/20 rounded-lg p-4 border border-warning-200 dark:border-warning-800">
                        <div class="flex items-center">
                            <div class="p-2 bg-warning-100 dark:bg-warning-900 rounded-lg">
                                <svg class="h-6 w-6 text-warning-600 dark:text-warning-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-warning-600 dark:text-warning-400">Groups</p>
                                <p class="text-2xl font-bold text-warning-900 dark:text-warning-100">{{ $organizations->sum(function($org) { return $org->organizationGroups->count(); }) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Organizations Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($organizations as $organization)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-all duration-200 overflow-hidden">
                            <!-- Organization Header -->
                            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center">
                                        <div class="h-12 w-12 bg-gradient-to-br from-primary-400 to-primary-600 rounded-xl flex items-center justify-center">
                                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                        </div>
                                        <div class="ml-4">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                                                {{ $organization->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $organization->users->count() }} members
                                            </p>
                                        </div>
                                    </div>
                                    <span class="status-badge {{ $organization->is_active ? 'status-badge-success' : 'status-badge-error' }}">
                                        {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>

                                @if($organization->description)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm leading-relaxed">
                                        {{ Str::limit($organization->description, 120) }}
                                    </p>
                                @endif
                            </div>

                            <!-- Organization Stats -->
                            <div class="p-4 bg-gray-50 dark:bg-gray-900">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-primary-600 dark:text-primary-400">
                                            {{ $organization->users->count() }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Members</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-lg font-bold text-success-600 dark:text-success-400">
                                            {{ $organization->organizationGroups->count() }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">Groups</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Organization Actions -->
                            <div class="p-4 border-t border-gray-200 dark:border-gray-700">
                                <div class="flex space-x-2">
                                    @can('view', $organization)
                                        <a href="{{ route('organizations.show', $organization) }}"
                                           class="flex-1 btn btn-primary text-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            View
                                        </a>
                                    @endcan

                                    @can('update', $organization)
                                        <a href="{{ route('organizations.edit', $organization) }}"
                                           class="flex-1 btn btn-warning text-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                            Edit
                                        </a>
                                    @endcan
                                </div>

                                @can('update', $organization)
                                    <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                        <div class="flex space-x-2 text-sm">
                                            <a href="{{ route('organizations.members', $organization) }}"
                                               class="flex-1 text-center px-3 py-2 text-info-700 bg-info-50 border border-info-200 rounded-md hover:bg-info-100 dark:bg-info-900 dark:text-info-300 dark:border-info-700 dark:hover:bg-info-800 transition-colors duration-200">
                                                <svg class="h-4 w-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Members
                                            </a>
                                            <a href="{{ route('organizations.groups.index', $organization) }}"
                                               class="flex-1 text-center px-3 py-2 text-success-700 bg-success-50 border border-success-200 rounded-md hover:bg-success-100 dark:bg-success-900 dark:text-success-300 dark:border-success-700 dark:hover:bg-success-800 transition-colors duration-200">
                                                <svg class="h-4 w-4 mx-auto mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                </svg>
                                                Groups
                                            </a>
                                        </div>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
                    <div class="text-center py-12">
                        <div class="flex flex-col items-center">
                            <svg class="h-20 w-20 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No organizations found</h3>
                            <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm mb-6">
                                Get started by creating your first organization to manage users and permissions.
                            </p>
                            @can('create', App\Models\Organization::class)
                                <a href="{{ route('organizations.create') }}" class="btn btn-primary">
                                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Create First Organization
                                </a>
                            @endcan
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
