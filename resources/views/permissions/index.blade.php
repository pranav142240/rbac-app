<x-layouts.app>    
    <!-- Enhanced Header Section -->
    <div class="mb-8">
        <div class="flex flex-col lg:flex-row lg:justify-between lg:items-start gap-6">
            <div class="flex-1">
                <div class="flex items-center space-x-3 mb-3">
                    <div class="p-3 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('System Permissions') }}</h1>
                        <p class="text-gray-600 dark:text-gray-400">{{ __('Manage and monitor permissions across your system') }}</p>
                    </div>
                </div>
            </div>

            <!-- Quick Stats Dashboard -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 min-w-fit">
                @php
                    $totalPermissions = array_sum(array_map(function($cat) { return count($cat['permissions']); }, $categorizedPermissions));
                    $activePermissions = 0;
                    $unusedPermissions = 0;
                    foreach($categorizedPermissions as $category) {
                        foreach($category['permissions'] as $permission) {
                            if($permission->roles->count() > 0) {
                                $activePermissions++;
                            } else {
                                $unusedPermissions++;
                            }
                        }
                    }
                @endphp
                
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm font-medium">Total</p>
                            <p class="text-2xl font-bold" id="total-count">{{ $totalPermissions }}</p>
                        </div>
                        <svg class="w-8 h-8 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm font-medium">Active</p>
                            <p class="text-2xl font-bold" id="active-count">{{ $activePermissions }}</p>
                        </div>
                        <svg class="w-8 h-8 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-yellow-500 to-yellow-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-yellow-100 text-sm font-medium">Unused</p>
                            <p class="text-2xl font-bold" id="unused-count">{{ $unusedPermissions }}</p>
                        </div>
                        <svg class="w-8 h-8 text-yellow-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-xl p-4 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm font-medium">Categories</p>
                            <p class="text-2xl font-bold">{{ count($categorizedPermissions) }}</p>
                        </div>
                        <svg class="w-8 h-8 text-purple-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"/>
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
                    <label for="search-permissions" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Search Permissions
                    </label>
                    <div class="relative">
                        <input 
                            type="text" 
                            id="search-permissions" 
                            class="form-input w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100"
                            placeholder="Search by permission name..."
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="md:col-span-3">
                    <label for="category-filter" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                        </svg>
                        Category
                    </label>
                    <select id="category-filter" class="form-select w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">All Categories</option>
                        @foreach ($categorizedPermissions as $categoryName => $category)
                            <option value="{{ $categoryName }}">{{ $categoryName }} ({{ count($category['permissions']) }})</option>
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
                    <select id="status-filter" class="form-select w-full border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:text-gray-100">
                        <option value="">All Status</option>
                        <option value="active">Active Only</option>
                        <option value="unused">Unused Only</option>
                    </select>
                </div>

                <!-- View Toggle -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        View
                    </label>
                    <div class="flex rounded-lg border border-gray-300 dark:border-gray-600 overflow-hidden">
                        <button id="grid-view" class="view-toggle flex-1 px-3 py-2 text-sm font-medium bg-primary-500 text-white hover:bg-primary-600 transition-colors">
                            <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM11 13a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                            </svg>
                        </button>
                        <button id="list-view" class="view-toggle flex-1 px-3 py-2 text-sm font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4 mx-auto" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Clear Filters -->
                <div class="md:col-span-1">
                    <button id="clear-all-filters" class="w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                        <svg class="w-4 h-4 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if (session('success'))
                <div class="alert alert-success mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-green-800 dark:text-green-200">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-4">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <span class="text-red-800 dark:text-red-200">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Grid View (Default) -->
            <div id="grid-container" class="grid grid-cols-1 lg:grid-cols-2 gap-6" style="display: grid;">
                @foreach ($categorizedPermissions as $categoryName => $category)
                    <div class="permission-category bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1" data-category="{{ $categoryName }}">
                        <!-- Enhanced Category Header -->
                        <div class="flex items-center mb-6 pb-4 border-b border-gray-200 dark:border-gray-600">
                            <div class="flex-shrink-0 p-3 rounded-xl shadow-md
                                @if($category['color'] === 'primary') bg-gradient-to-br from-primary-500 to-primary-600
                                @elseif($category['color'] === 'success') bg-gradient-to-br from-success-500 to-success-600
                                @elseif($category['color'] === 'warning') bg-gradient-to-br from-warning-500 to-warning-600
                                @elseif($category['color'] === 'error') bg-gradient-to-br from-error-500 to-error-600
                                @else bg-gradient-to-br from-info-500 to-info-600
                                @endif">
                                @switch($category['icon'])
                                    @case('users')
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        @break
                                    @case('user-circle')
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @break
                                    @case('shield-check')
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        @break
                                    @case('office-building')
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        @break
                                    @case('user-group')
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-4 flex-1">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $categoryName }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $category['description'] }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium shadow-sm
                                    @if($category['color'] === 'primary') bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200
                                    @elseif($category['color'] === 'success') bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200
                                    @elseif($category['color'] === 'warning') bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200
                                    @elseif($category['color'] === 'error') bg-error-100 text-error-800 dark:bg-error-900 dark:text-error-200
                                    @else bg-info-100 text-info-800 dark:bg-info-900 dark:text-info-200
                                    @endif">
                                    {{ count($category['permissions']) }} {{ count($category['permissions']) === 1 ? 'permission' : 'permissions' }}
                                </span>
                            </div>
                        </div>

                        <!-- Enhanced Permissions List -->
                        <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                            @foreach ($category['permissions'] as $permission)
                                <div class="permission-item bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-600 transition-all duration-200 hover:shadow-md group {{ $permission->roles->count() > 0 ? 'permission-status-active' : 'permission-status-unused' }}" 
                                     data-permission-name="{{ strtolower($permission->name) }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                                    {{ $permission->name }}
                                                </h4>
                                                <div class="flex items-center space-x-2">
                                                    @if ($permission->guard_name !== 'web')
                                                        <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md">
                                                            {{ $permission->guard_name }}
                                                        </span>
                                                    @endif
                                                    @if ($permission->roles->count() > 0)
                                                        <div class="flex items-center space-x-1">
                                                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                                            <span class="text-xs text-green-600 dark:text-green-400 font-medium">Active</span>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center space-x-1">
                                                            <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                                            <span class="text-xs text-yellow-600 dark:text-yellow-400 font-medium">Unused</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Role assignments with enhanced UI -->
                                            @if ($permission->roles->count() > 0)
                                                <div class="mt-3">
                                                    <div class="flex items-center mb-2">
                                                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">
                                                            Assigned to {{ $permission->roles->count() }} {{ $permission->roles->count() === 1 ? 'role' : 'roles' }}
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($permission->roles->take(3) as $role)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200 border border-primary-200 dark:border-primary-700">
                                                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $role->name }}
                                                            </span>
                                                        @endforeach
                                                        @if($permission->roles->count() > 3)
                                                            <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                                +{{ $permission->roles->count() - 3 }} more
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mt-3 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                                    <div class="flex items-center">
                                                        <svg class="h-4 w-4 text-yellow-600 dark:text-yellow-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                        </svg>
                                                        <span class="text-xs text-yellow-800 dark:text-yellow-200 font-medium">Not assigned to any roles</span>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            @if(empty($category['permissions']))
                                <div class="text-center py-8">
                                    <svg class="h-12 w-12 text-gray-400 mx-auto mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">No permissions in this category</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- List View (Hidden by default) -->
            <div id="list-container" class="space-y-4" style="display: none;">
                @foreach ($categorizedPermissions as $categoryName => $category)
                    @foreach ($category['permissions'] as $permission)
                        <div class="permission-item permission-category bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-600 transition-all duration-200 hover:shadow-md {{ $permission->roles->count() > 0 ? 'permission-status-active' : 'permission-status-unused' }}"
                             data-category="{{ $categoryName }}"
                             data-permission-name="{{ strtolower($permission->name) }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <div class="flex-shrink-0">
                                        @if ($permission->roles->count() > 0)
                                            <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                                        @else
                                            <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-gray-100 truncate">
                                            {{ $permission->name }}
                                        </h4>
                                        <div class="flex items-center space-x-4 mt-1">
                                            <span class="text-sm text-gray-500 dark:text-gray-400">{{ $categoryName }}</span>
                                            @if ($permission->guard_name !== 'web')
                                                <span class="px-2 py-1 text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 rounded-md">
                                                    {{ $permission->guard_name }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-4">
                                    @if ($permission->roles->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach ($permission->roles->take(2) as $role)
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-primary-100 dark:bg-primary-900 text-primary-800 dark:text-primary-200">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                            @if($permission->roles->count() > 2)
                                                <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                    +{{ $permission->roles->count() - 2 }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center space-x-1 text-green-600 dark:text-green-400">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm font-medium">Active</span>
                                        </div>
                                    @else
                                        <div class="flex items-center space-x-1 text-yellow-600 dark:text-yellow-400">
                                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                            </svg>
                                            <span class="text-sm font-medium">Unused</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>

            <!-- Enhanced Empty States -->
            @if(empty($categorizedPermissions))
                <div class="text-center py-16">
                    <div class="flex flex-col items-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-full flex items-center justify-center mb-6">
                            <svg class="h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-2">No permissions found</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-md">
                            System permissions will appear here once they are defined. Contact your administrator to set up permissions.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Custom Styles -->
    <style>
        .custom-scrollbar {
            scrollbar-width: thin;
            scrollbar-color: #CBD5E0 #F7FAFC;
        }
        
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #F7FAFC;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #CBD5E0;
            border-radius: 10px;
        }
        
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #A0AEC0;
        }

        .dark .custom-scrollbar {
            scrollbar-color: #4A5568 #2D3748;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-track {
            background: #2D3748;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #4A5568;
        }
        
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #718096;
        }

        .permission-item {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .view-toggle.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>

    <!-- Enhanced JavaScript with Search, Filters, and View Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all the elements
            const searchInput = document.getElementById('search-permissions');
            const categoryFilter = document.getElementById('category-filter');
            const statusFilter = document.getElementById('status-filter');
            const clearAllFilters = document.getElementById('clear-all-filters');
            const gridView = document.getElementById('grid-view');
            const listView = document.getElementById('list-view');
            const gridContainer = document.getElementById('grid-container');
            const listContainer = document.getElementById('list-container');
            
            // Stats elements
            const totalCountElement = document.getElementById('total-count');
            const activeCountElement = document.getElementById('active-count');
            const unusedCountElement = document.getElementById('unused-count');
            
            // Store original counts
            const originalCounts = {
                total: parseInt(totalCountElement.textContent),
                active: parseInt(activeCountElement.textContent),
                unused: parseInt(unusedCountElement.textContent)
            };

            // View toggle functionality
            function setActiveView(activeButton, inactiveButton, showContainer, hideContainer) {
                activeButton.classList.add('active', 'bg-primary-500', 'text-white');
                activeButton.classList.remove('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                
                inactiveButton.classList.remove('active', 'bg-primary-500', 'text-white');
                inactiveButton.classList.add('bg-gray-100', 'dark:bg-gray-700', 'text-gray-700', 'dark:text-gray-300');
                
                showContainer.style.display = showContainer.id === 'grid-container' ? 'grid' : 'block';
                hideContainer.style.display = 'none';
            }

            gridView.addEventListener('click', function() {
                setActiveView(gridView, listView, gridContainer, listContainer);
                localStorage.setItem('permissions-view', 'grid');
            });

            listView.addEventListener('click', function() {
                setActiveView(listView, gridView, listContainer, gridContainer);
                localStorage.setItem('permissions-view', 'list');
            });

            // Restore saved view preference
            const savedView = localStorage.getItem('permissions-view');
            if (savedView === 'list') {
                listView.click();
            }

            // Filter functionality
            function applyFilters() {
                const searchTerm = searchInput.value.toLowerCase().trim();
                const selectedCategory = categoryFilter.value;
                const selectedStatus = statusFilter.value;
                
                // Get all permission items in both views
                const gridItems = gridContainer.querySelectorAll('.permission-category');
                const listItems = listContainer.querySelectorAll('.permission-item');
                
                let visibleCounts = { total: 0, active: 0, unused: 0 };
                
                // Filter grid view
                gridItems.forEach(function(categoryElement) {
                    const categoryName = categoryElement.getAttribute('data-category');
                    const permissionItems = categoryElement.querySelectorAll('.permission-item');
                    
                    let categoryHasVisibleItems = false;
                    
                    permissionItems.forEach(function(item) {
                        const permissionName = item.getAttribute('data-permission-name');
                        const isActive = item.classList.contains('permission-status-active');
                        const isUnused = item.classList.contains('permission-status-unused');
                        
                        let shouldShow = true;
                        
                        // Apply search filter
                        if (searchTerm && !permissionName.includes(searchTerm)) {
                            shouldShow = false;
                        }
                        
                        // Apply category filter
                        if (selectedCategory && categoryName !== selectedCategory) {
                            shouldShow = false;
                        }
                        
                        // Apply status filter
                        if (selectedStatus === 'active' && !isActive) {
                            shouldShow = false;
                        } else if (selectedStatus === 'unused' && !isUnused) {
                            shouldShow = false;
                        }
                        
                        if (shouldShow) {
                            item.style.display = 'block';
                            categoryHasVisibleItems = true;
                            visibleCounts.total++;
                            if (isActive) visibleCounts.active++;
                            if (isUnused) visibleCounts.unused++;
                        } else {
                            item.style.display = 'none';
                        }
                    });
                    
                    // Show/hide category based on whether it has visible items
                    if (categoryHasVisibleItems) {
                        categoryElement.style.display = 'block';
                    } else {
                        categoryElement.style.display = 'none';
                    }
                });
                
                // Filter list view
                listItems.forEach(function(item) {
                    const permissionName = item.getAttribute('data-permission-name');
                    const categoryName = item.getAttribute('data-category');
                    const isActive = item.classList.contains('permission-status-active');
                    const isUnused = item.classList.contains('permission-status-unused');
                    
                    let shouldShow = true;
                    
                    // Apply search filter
                    if (searchTerm && !permissionName.includes(searchTerm)) {
                        shouldShow = false;
                    }
                    
                    // Apply category filter
                    if (selectedCategory && categoryName !== selectedCategory) {
                        shouldShow = false;
                    }
                    
                    // Apply status filter
                    if (selectedStatus === 'active' && !isActive) {
                        shouldShow = false;
                    } else if (selectedStatus === 'unused' && !isUnused) {
                        shouldShow = false;
                    }
                    
                    item.style.display = shouldShow ? 'block' : 'none';
                });
                
                // Update counters
                totalCountElement.textContent = visibleCounts.total;
                activeCountElement.textContent = visibleCounts.active;
                unusedCountElement.textContent = visibleCounts.unused;
                
                // Show empty state if no results
                showEmptyState(visibleCounts.total === 0);
            }

            function showEmptyState(show) {
                const existingEmptyState = document.querySelector('.filter-empty-state');
                
                if (show) {
                    if (!existingEmptyState) {
                        const emptyStateHtml = `
                            <div class="filter-empty-state text-center py-16 col-span-full">
                                <div class="flex flex-col items-center">
                                    <div class="w-20 h-20 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-full flex items-center justify-center mb-6">
                                        <svg class="h-10 w-10 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No permissions match your filters</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm mb-4">
                                        Try adjusting your search terms or filters to find what you're looking for.
                                    </p>
                                    <button id="clear-filters-from-empty" class="px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors">
                                        Clear All Filters
                                    </button>
                                </div>
                            </div>
                        `;
                        
                        // Add to both containers
                        gridContainer.insertAdjacentHTML('beforeend', emptyStateHtml);
                        listContainer.insertAdjacentHTML('beforeend', emptyStateHtml.replace('col-span-full', ''));
                        
                        // Add click handler for the clear button in empty state
                        document.getElementById('clear-filters-from-empty').addEventListener('click', clearAllFiltersFunction);
                    }
                } else {
                    if (existingEmptyState) {
                        existingEmptyState.remove();
                    }
                    // Also remove from list container
                    const listEmptyState = listContainer.querySelector('.filter-empty-state');
                    if (listEmptyState) {
                        listEmptyState.remove();
                    }
                }
            }

            function clearAllFiltersFunction() {
                searchInput.value = '';
                categoryFilter.value = '';
                statusFilter.value = '';
                
                // Reset counters
                totalCountElement.textContent = originalCounts.total;
                activeCountElement.textContent = originalCounts.active;
                unusedCountElement.textContent = originalCounts.unused;
                
                // Show all items
                document.querySelectorAll('.permission-category, .permission-item').forEach(function(item) {
                    item.style.display = item.classList.contains('permission-category') ? 'block' : 'block';
                });
                
                // Hide empty state
                showEmptyState(false);
            }

            // Event listeners
            searchInput.addEventListener('input', debounce(applyFilters, 300));
            categoryFilter.addEventListener('change', applyFilters);
            statusFilter.addEventListener('change', applyFilters);
            clearAllFilters.addEventListener('click', clearAllFiltersFunction);

            // Debounce function for search input
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }

            // Auto-save filter state
            function saveFilterState() {
                const state = {
                    search: searchInput.value,
                    category: categoryFilter.value,
                    status: statusFilter.value
                };
                localStorage.setItem('permissions-filters', JSON.stringify(state));
            }

            function loadFilterState() {
                const saved = localStorage.getItem('permissions-filters');
                if (saved) {
                    try {
                        const state = JSON.parse(saved);
                        searchInput.value = state.search || '';
                        categoryFilter.value = state.category || '';
                        statusFilter.value = state.status || '';
                        if (state.search || state.category || state.status) {
                            applyFilters();
                        }
                    } catch (e) {
                        // Invalid saved state, ignore
                    }
                }
            }

            // Save state on change
            [searchInput, categoryFilter, statusFilter].forEach(element => {
                element.addEventListener('change', saveFilterState);
            });

            // Load saved state on page load
            loadFilterState();
        });
    </script>
</x-layouts.app>
