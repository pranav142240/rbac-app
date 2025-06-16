<x-layouts.app>    
    <div class="mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('System Permissions Overview') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('View all system permissions organized by category and their role assignments. This is a read-only view for monitoring permission distribution across roles.') }}</p>
            </div>
            <div class="flex flex-col items-end space-y-4">
                <!-- Category Filter -->
                <div class="flex items-center space-x-3">
                    <label for="category-filter" class="text-sm font-medium text-gray-700 dark:text-gray-300">Filter by Category:</label>
                    <div class="relative">
                        <select id="category-filter" class="form-input text-sm min-w-48 pr-10">
                            <option value="">All Categories</option>
                            @foreach ($categorizedPermissions as $categoryName => $category)
                                <option value="{{ $categoryName }}">{{ $categoryName }} ({{ count($category['permissions']) }})</option>
                            @endforeach
                        </select>
                        <!-- Filter Icon -->
                        <div class="absolute inset-y-0 right-8 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"/>
                            </svg>
                        </div>
                    </div>
                    <!-- Clear Filter Button (hidden by default) -->
                    <button id="clear-filter" class="hidden text-sm text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                        Clear Filter
                    </button>
                </div>
                
                <!-- Permission Summary Stats -->
                <div class="flex items-center space-x-6 text-sm">
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-primary-500 rounded-full"></div>
                        <span class="text-gray-600 dark:text-gray-400">{{ array_sum(array_map(function($cat) { return count($cat['permissions']); }, $categorizedPermissions)) }} Total Permissions</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-success-500 rounded-full"></div>
                        <span class="text-gray-600 dark:text-gray-400">{{ count($categorizedPermissions) }} Categories</span>
                    </div>
                    @php
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
                    <div class="flex items-center space-x-2">
                        <div class="w-3 h-3 bg-success-400 rounded-full"></div>
                        <span class="text-gray-600 dark:text-gray-400" id="active-count">{{ $activePermissions }} Active</span>
                    </div>
                    @if($unusedPermissions > 0)
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 bg-warning-500 rounded-full"></div>
                            <span class="text-gray-600 dark:text-gray-400" id="unused-count">{{ $unusedPermissions }} Unused</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if (session('success'))
                <div class="alert alert-success mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Permission Categories Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6" id="categories-container">
                @foreach ($categorizedPermissions as $categoryName => $category)
                    <div class="permission-category bg-gray-50 dark:bg-gray-700 rounded-xl p-6 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition-shadow duration-200" data-category="{{ $categoryName }}">
                        <!-- Category Header -->
                        <div class="flex items-center mb-6">
                            <div class="flex-shrink-0 p-3 
                                @if($category['color'] === 'primary') bg-primary-100 dark:bg-primary-900
                                @elseif($category['color'] === 'success') bg-success-100 dark:bg-success-900
                                @elseif($category['color'] === 'warning') bg-warning-100 dark:bg-warning-900
                                @elseif($category['color'] === 'error') bg-error-100 dark:bg-error-900
                                @else bg-info-100 dark:bg-info-900
                                @endif rounded-xl">
                                @switch($category['icon'])
                                    @case('users')
                                        <svg class="w-8 h-8 
                                            @if($category['color'] === 'primary') text-primary-600 dark:text-primary-400
                                            @elseif($category['color'] === 'success') text-success-600 dark:text-success-400
                                            @elseif($category['color'] === 'warning') text-warning-600 dark:text-warning-400
                                            @elseif($category['color'] === 'error') text-error-600 dark:text-error-400
                                            @else text-info-600 dark:text-info-400
                                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                        @break
                                    @case('user-circle')
                                        <svg class="w-8 h-8 
                                            @if($category['color'] === 'primary') text-primary-600 dark:text-primary-400
                                            @elseif($category['color'] === 'success') text-success-600 dark:text-success-400
                                            @elseif($category['color'] === 'warning') text-warning-600 dark:text-warning-400
                                            @elseif($category['color'] === 'error') text-error-600 dark:text-error-400
                                            @else text-info-600 dark:text-info-400
                                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        @break
                                    @case('shield-check')
                                        <svg class="w-8 h-8 
                                            @if($category['color'] === 'primary') text-primary-600 dark:text-primary-400
                                            @elseif($category['color'] === 'success') text-success-600 dark:text-success-400
                                            @elseif($category['color'] === 'warning') text-warning-600 dark:text-warning-400
                                            @elseif($category['color'] === 'error') text-error-600 dark:text-error-400
                                            @else text-info-600 dark:text-info-400
                                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        @break
                                    @case('office-building')
                                        <svg class="w-8 h-8 
                                            @if($category['color'] === 'primary') text-primary-600 dark:text-primary-400
                                            @elseif($category['color'] === 'success') text-success-600 dark:text-success-400
                                            @elseif($category['color'] === 'warning') text-warning-600 dark:text-warning-400
                                            @elseif($category['color'] === 'error') text-error-600 dark:text-error-400
                                            @else text-info-600 dark:text-info-400
                                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                        @break
                                    @case('user-group')
                                        <svg class="w-8 h-8 
                                            @if($category['color'] === 'primary') text-primary-600 dark:text-primary-400
                                            @elseif($category['color'] === 'success') text-success-600 dark:text-success-400
                                            @elseif($category['color'] === 'warning') text-warning-600 dark:text-warning-400
                                            @elseif($category['color'] === 'error') text-error-600 dark:text-error-400
                                            @else text-info-600 dark:text-info-400
                                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                        </svg>
                                        @break
                                    @default
                                        <svg class="w-8 h-8 
                                            @if($category['color'] === 'primary') text-primary-600 dark:text-primary-400
                                            @elseif($category['color'] === 'success') text-success-600 dark:text-success-400
                                            @elseif($category['color'] === 'warning') text-warning-600 dark:text-warning-400
                                            @elseif($category['color'] === 'error') text-error-600 dark:text-error-400
                                            @else text-info-600 dark:text-info-400
                                            @endif" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                @endswitch
                            </div>
                            <div class="ml-4 flex-1">
                                <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100">{{ $categoryName }}</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $category['description'] }}</p>
                            </div>
                            <div class="text-right">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                                    @if($category['color'] === 'primary') bg-primary-100 text-primary-800 dark:bg-primary-900 dark:text-primary-200
                                    @elseif($category['color'] === 'success') bg-success-100 text-success-800 dark:bg-success-900 dark:text-success-200
                                    @elseif($category['color'] === 'warning') bg-warning-100 text-warning-800 dark:bg-warning-900 dark:text-warning-200
                                    @elseif($category['color'] === 'error') bg-error-100 text-error-800 dark:bg-error-900 dark:text-error-200
                                    @else bg-info-100 text-info-800 dark:bg-info-900 dark:text-info-200
                                    @endif">
                                    {{ count($category['permissions']) }} permissions
                                </span>
                            </div>
                        </div>

                        <!-- Permissions List -->
                        <div class="space-y-4">
                            @foreach ($category['permissions'] as $permission)
                                <div class="bg-white dark:bg-gray-800 rounded-lg p-5 border border-gray-200 dark:border-gray-600 hover:border-primary-300 dark:hover:border-primary-600 transition-all duration-200 hover:shadow-md {{ $permission->roles->count() > 0 ? 'permission-status-active' : 'permission-status-unused' }}">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-3">
                                                <div class="flex items-center">
                                                    <h4 class="text-base font-semibold text-gray-900 dark:text-gray-100 truncate">
                                                        {{ $permission->name }}
                                                    </h4>
                                                    @if ($permission->guard_name !== 'web')
                                                        <span class="ml-3 status-badge status-badge-secondary text-xs">
                                                            {{ $permission->guard_name }}
                                                        </span>
                                                    @endif
                                                </div>
                                                <!-- Permission Status Indicator -->
                                                <div class="flex items-center">
                                                    @if ($permission->roles->count() > 0)
                                                        <div class="flex items-center space-x-1">
                                                            <svg class="h-4 w-4 text-success-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span class="text-xs text-success-600 dark:text-success-400 font-medium">Active</span>
                                                        </div>
                                                    @else
                                                        <div class="flex items-center space-x-1">
                                                            <svg class="h-4 w-4 text-warning-500" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span class="text-xs text-warning-600 dark:text-warning-400 font-medium">Unused</span>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <!-- Roles using this permission -->
                                            @if ($permission->roles->count() > 0)
                                                <div class="mt-3">
                                                    <div class="flex items-center mb-2">
                                                        <svg class="h-4 w-4 text-gray-500 dark:text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                                        </svg>
                                                        <span class="text-xs font-medium text-gray-600 dark:text-gray-400">Assigned to {{ $permission->roles->count() }} role{{ $permission->roles->count() > 1 ? 's' : '' }}</span>
                                                    </div>
                                                    <div class="flex flex-wrap gap-1">
                                                        @foreach ($permission->roles as $role)
                                                            <span class="status-badge status-badge-primary text-xs inline-flex items-center">
                                                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                                </svg>
                                                                {{ $role->name }}
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @else
                                                <div class="mt-3 flex items-center">
                                                    <svg class="h-4 w-4 text-warning-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                                    </svg>
                                                    <span class="text-xs text-warning-600 dark:text-warning-400 font-medium italic">Not assigned to any roles</span>
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

            @if(empty($categorizedPermissions))
                <div class="text-center py-12">
                    <div class="flex flex-col items-center">
                        <svg class="h-20 w-20 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No permissions found</h3>
                        <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm">
                            System permissions will appear here once they are defined.
                        </p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Category Filter JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categoryFilter = document.getElementById('category-filter');
            const clearFilterBtn = document.getElementById('clear-filter');
            const categoriesContainer = document.getElementById('categories-container');
            const activeCountElement = document.getElementById('active-count');
            const unusedCountElement = document.getElementById('unused-count');
            
            // Store original counts
            const originalActiveCount = activeCountElement ? parseInt(activeCountElement.textContent.split(' ')[0]) : 0;
            const originalUnusedCount = unusedCountElement ? parseInt(unusedCountElement.textContent.split(' ')[0]) : 0;
            
            function updateFilter() {
                const selectedCategory = categoryFilter.value;
                const categories = categoriesContainer.querySelectorAll('.permission-category');
                
                let visibleActiveCount = 0;
                let visibleUnusedCount = 0;
                
                categories.forEach(function(categoryElement) {
                    const categoryName = categoryElement.getAttribute('data-category');
                    
                    if (selectedCategory === '' || categoryName === selectedCategory) {
                        // Show category
                        categoryElement.style.display = 'block';
                        
                        // Count active and unused permissions in this category
                        const activePermissions = categoryElement.querySelectorAll('.permission-status-active').length;
                        const unusedPermissions = categoryElement.querySelectorAll('.permission-status-unused').length;
                        visibleActiveCount += activePermissions;
                        visibleUnusedCount += unusedPermissions;
                    } else {
                        // Hide category
                        categoryElement.style.display = 'none';
                    }
                });
                
                // Update counters
                if (activeCountElement) {
                    activeCountElement.textContent = visibleActiveCount + ' Active';
                }
                if (unusedCountElement) {
                    unusedCountElement.textContent = visibleUnusedCount + ' Unused';
                }
                
                // Show/hide clear filter button
                if (selectedCategory !== '') {
                    clearFilterBtn.classList.remove('hidden');
                } else {
                    clearFilterBtn.classList.add('hidden');
                }
                
                // Show/hide empty state
                const visibleCategories = categoriesContainer.querySelectorAll('.permission-category[style="display: block"], .permission-category:not([style*="display: none"])');
                const existingEmptyState = document.querySelector('.empty-state-filter');
                
                if (visibleCategories.length === 0 && selectedCategory !== '') {
                    if (!existingEmptyState) {
                        const emptyStateHtml = `
                            <div class="text-center py-12 empty-state-filter">
                                <div class="flex flex-col items-center">
                                    <svg class="h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No permissions found in "${selectedCategory}" category</h3>
                                    <p class="text-gray-500 dark:text-gray-400 text-center max-w-sm">
                                        Try selecting a different category or view all categories.
                                    </p>
                                </div>
                            </div>
                        `;
                        categoriesContainer.insertAdjacentHTML('afterend', emptyStateHtml);
                    }
                } else {
                    // Remove filter empty state if it exists
                    if (existingEmptyState) {
                        existingEmptyState.remove();
                    }
                }
            }
            
            categoryFilter.addEventListener('change', updateFilter);
            
            clearFilterBtn.addEventListener('click', function() {
                categoryFilter.value = '';
                updateFilter();
            });
        });
    </script>
</x-layouts.app>
