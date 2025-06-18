<x-layouts.app>    
    <!-- Enhanced Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="p-3 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Organization Groups') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Manage your group memberships across organizations') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Dashboard -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 min-w-fit">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total Groups</p>
                            <p class="text-2xl font-bold" id="total-count">{{ $totalGroups }}</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active Groups</p>
                            <p class="text-2xl font-bold" id="active-count">{{ $activeGroups }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Total Members</p>
                            <p class="text-2xl font-bold" id="members-count">{{ $totalMembers }}</p>
                        </div>
                        <svg class="w-8 h-8 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Organizations</p>
                            <p class="text-2xl font-bold" id="orgs-count">{{ $uniqueOrganizations }}</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-5L9 2H4z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Enhanced Search and Filter Section -->
        <div class="mt-8 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 p-6">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                <!-- Search Input -->
                <div class="md:col-span-4">
                    <label for="search-groups" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Groups
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search-groups" 
                            class="block w-full pl-10 pr-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-300 transition-all"
                            placeholder="Search by group name, organization, or description..."
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Organization Filter -->
                <div class="md:col-span-3">
                    <label for="org-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Organization
                    </label>
                    <select id="org-filter" class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Organizations</option>
                        @foreach($groups->pluck('organization')->unique('id') as $org)
                            <option value="{{ $org->id }}">{{ $org->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Filter -->
                <div class="md:col-span-2">
                    <label for="status-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Status
                    </label>
                    <select id="status-filter" class="block w-full px-3 py-3 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-white">
                        <option value="">All Groups</option>
                        <option value="active">Active Only</option>
                        <option value="inactive">Inactive Only</option>
                    </select>
                </div>

                <!-- Action Buttons -->
                <div class="md:col-span-3 flex space-x-2">
                    <a href="{{ route('organizations.index') }}" class="flex-1 bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-3 rounded-lg hover:from-gray-600 hover:to-gray-700 transition-all shadow-lg flex items-center justify-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        <span class="font-medium">Organizations</span>
                    </a>
                    
                    <button id="export-btn" class="px-4 py-3 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm flex items-center space-x-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Export</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups Display -->
    <div class="space-y-6">
        @forelse($groups as $group)
            <div class="group-card bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-200" 
                 data-group-name="{{ strtolower($group->name) }}" 
                 data-org-name="{{ strtolower($group->organization->name) }}"
                 data-org-id="{{ $group->organization->id }}"
                 data-members-count="{{ $group->users_count }}"
                 data-status="{{ $group->is_active ? 'active' : 'inactive' }}">
                
                <!-- Group Header -->
                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-600 px-6 py-4 border-b border-gray-200 dark:border-gray-600">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="p-3 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl shadow-lg">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-1">
                                    {{ $group->name }}
                                </h3>
                                <div class="flex items-center space-x-4">
                                    <span class="inline-flex items-center text-sm text-primary-600 dark:text-primary-400 font-medium">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        {{ $group->organization->name }}
                                    </span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">•</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                        ID: {{ $group->id }}
                                    </span>
                                </div>
                                @if($group->description)
                                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-2">
                                        {{ $group->description }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Status and Actions -->
                        <div class="flex items-center space-x-3">
                            <!-- Status Badge -->
                            @if($group->is_active)
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Active
                                </span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Inactive
                                </span>
                            @endif
                            
                            <!-- Action Menu -->
                            <div class="relative">
                                <button class="group-menu-btn p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" data-group-id="{{ $group->id }}">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                                    </svg>
                                </button>
                                
                                <div class="group-menu hidden absolute right-0 top-full mt-1 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-10">
                                    <a href="{{ route('organization-groups.show', $group) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-t-lg transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View Group Details
                                    </a>
                                    
                                    <a href="{{ route('organizations.show', $group->organization) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                        View Organization
                                    </a>
                                    
                                    <a href="{{ route('organizations.groups.index', $group->organization) }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-b-lg transition-colors">
                                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                        </svg>
                                        All Org Groups
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Group Stats and Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Members Stats -->
                        <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="text-3xl font-bold text-blue-600 dark:text-blue-400 mb-1">
                                {{ $group->users_count }}
                            </div>
                            <div class="text-sm text-blue-700 dark:text-blue-300 font-medium">
                                {{ Str::plural('Member', $group->users_count) }}
                            </div>
                        </div>
                        
                        <!-- Created Date -->
                        <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="text-sm font-bold text-green-600 dark:text-green-400 mb-1">
                                {{ $group->created_at->format('M d, Y') }}
                            </div>
                            <div class="text-sm text-green-700 dark:text-green-300 font-medium">
                                Created
                            </div>
                        </div>
                        
                        <!-- Last Activity -->
                        <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-lg border border-purple-200 dark:border-purple-700">
                            <div class="text-sm font-bold text-purple-600 dark:text-purple-400 mb-1">
                                {{ $group->updated_at->diffForHumans() }}
                            </div>
                            <div class="text-sm text-purple-700 dark:text-purple-300 font-medium">
                                Last Updated
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="{{ route('organization-groups.show', $group) }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            View Details
                        </a>
                        
                        <a href="{{ route('organizations.show', $group->organization) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white text-sm font-medium rounded-lg transition-colors">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                            </svg>
                            {{ $group->organization->name }}
                        </a>
                        
                        <span class="inline-flex items-center px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                            {{ $group->users_count }} {{ Str::plural('Member', $group->users_count) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No groups found</h3>
                <p class="text-gray-500 dark:text-gray-400 mb-6">You are not a member of any organization groups yet.</p>
                <a href="{{ route('organizations.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    Browse Organizations
                </a>
            </div>
        @endforelse
    </div>

    <!-- Enhanced JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Search and filter functionality
            const searchInput = document.getElementById('search-groups');
            const orgFilter = document.getElementById('org-filter');
            const statusFilter = document.getElementById('status-filter');
            const groupCards = document.querySelectorAll('.group-card');

            function filterGroups() {
                const searchTerm = searchInput.value.toLowerCase();
                const selectedOrg = orgFilter.value;
                const selectedStatus = statusFilter.value;

                let visibleCount = 0;
                let visibleActiveCount = 0;
                let visibleMembersCount = 0;
                let visibleOrgsSet = new Set();

                groupCards.forEach(card => {
                    const groupName = card.dataset.groupName;
                    const orgName = card.dataset.orgName;
                    const orgId = card.dataset.orgId;
                    const membersCount = parseInt(card.dataset.membersCount);
                    const status = card.dataset.status;

                    let visible = true;

                    // Search filter
                    if (searchTerm && !groupName.includes(searchTerm) && !orgName.includes(searchTerm)) {
                        visible = false;
                    }

                    // Organization filter
                    if (selectedOrg && selectedOrg !== orgId) {
                        visible = false;
                    }

                    // Status filter
                    if (selectedStatus && selectedStatus !== status) {
                        visible = false;
                    }

                    card.style.display = visible ? 'block' : 'none';
                    
                    if (visible) {
                        visibleCount++;
                        if (status === 'active') visibleActiveCount++;
                        visibleMembersCount += membersCount;
                        visibleOrgsSet.add(orgId);
                    }
                });

                // Update counters
                document.getElementById('total-count').textContent = visibleCount;
                document.getElementById('active-count').textContent = visibleActiveCount;
                document.getElementById('members-count').textContent = visibleMembersCount;
                document.getElementById('orgs-count').textContent = visibleOrgsSet.size;
            }

            // Event listeners
            searchInput.addEventListener('input', filterGroups);
            orgFilter.addEventListener('change', filterGroups);
            statusFilter.addEventListener('change', filterGroups);

            // Group menu functionality
            document.querySelectorAll('.group-menu-btn').forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;
                    
                    // Close other open menus
                    document.querySelectorAll('.group-menu').forEach(m => {
                        if (m !== menu) m.classList.add('hidden');
                    });
                    
                    menu.classList.toggle('hidden');
                });
            });

            // Close menus when clicking outside
            document.addEventListener('click', function() {
                document.querySelectorAll('.group-menu').forEach(menu => {
                    menu.classList.add('hidden');
                });
            });

            // Export functionality
            document.getElementById('export-btn').addEventListener('click', function() {
                const visibleGroups = [];
                document.querySelectorAll('.group-card[style="display: block"], .group-card:not([style*="display: none"])').forEach(card => {
                    visibleGroups.push({
                        name: card.dataset.groupName,
                        organization: card.dataset.orgName,
                        members: card.dataset.membersCount,
                        status: card.dataset.status
                    });
                });
                
                const csv = 'Group Name,Organization,Members,Status\n' + 
                    visibleGroups.map(group => `${group.name},${group.organization},${group.members},${group.status}`).join('\n');
                
                const blob = new Blob([csv], { type: 'text/csv' });
                const url = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = url;
                a.download = 'organization-groups-export.csv';
                a.click();
                window.URL.revokeObjectURL(url);
            });
        });
    </script>
</x-layouts.app>
