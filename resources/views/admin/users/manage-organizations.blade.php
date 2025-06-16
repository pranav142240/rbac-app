<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Manage User Organizations & Groups') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Assign or remove organizations and groups for') }} <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.show', $user) }}" 
                   class="btn btn-secondary">
                    <x-icon name="eye" class="h-4 w-4 mr-2" />
                    {{ __('View User') }}
                </a>
                <a href="{{ route('admin.users.index') }}" 
                   class="btn btn-secondary">
                    <x-icon name="list" class="h-4 w-4 mr-2" />
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-success-100 dark:bg-success-900 rounded-full flex items-center justify-center mr-4">
                <span class="text-success-600 dark:text-success-400 font-bold text-lg">
                    {{ $user->initials() }}
                </span>
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $user->name }}</h2>
                <p class="text-gray-600 dark:text-gray-400">{{ $user->email }}</p>
                @if($user->phone)
                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $user->phone }}</p>
                @endif
            </div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update-organizations', $user) }}" class="space-y-6">
        @csrf

        <!-- Organizations Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Organization Memberships') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Select which organizations this user belongs to') }}</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($organizations as $organization)
                        <label class="flex items-start p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ $user->belongsToOrganization($organization) ? 'bg-success-50 dark:bg-success-900/20 border-success-300 dark:border-success-600' : '' }}">
                            <input type="checkbox" name="organizations[]" value="{{ $organization->id }}" 
                                   {{ $user->belongsToOrganization($organization) ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-300 text-success-600 shadow-sm focus:border-success-500 focus:ring-success-500 organization-checkbox"
                                   data-org-id="{{ $organization->id }}">
                            <div class="ml-3 flex-1">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $organization->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $organization->users->count() }} {{ __('members') }} • {{ $organization->organizationGroups->count() }} {{ __('groups') }}
                                </div>
                                @if($organization->description)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        {{ Str::limit($organization->description, 80) }}
                                    </div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('organizations')
                    <p class="mt-2 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Organization Groups Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Group Memberships') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Select groups (only available for selected organizations)') }}</p>
            </div>
            <div class="p-6">
                @if($organizationGroups->count() > 0)
                    @foreach($organizationGroups->groupBy('organization.name') as $orgName => $groups)
                        @php $orgId = $groups->first()->organization->id; @endphp
                        <div class="mb-6 organization-groups" data-org-id="{{ $orgId }}">
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <x-icon name="office-building" class="h-4 w-4 mr-2" />
                                {{ $orgName }}
                                <span class="ml-2 text-xs text-gray-500 dark:text-gray-400 org-status" data-org-id="{{ $orgId }}">
                                    ({{ $user->belongsToOrganization($groups->first()->organization) ? 'Available' : 'Select organization first' }})
                                </span>
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($groups as $group)
                                    <label class="flex items-start p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ $user->belongsToOrganizationGroup($group) ? 'bg-info-50 dark:bg-info-900/20 border-info-300 dark:border-info-600' : '' }} group-checkbox-container"
                                           data-org-id="{{ $orgId }}">
                                        <input type="checkbox" name="organization_groups[]" value="{{ $group->id }}" 
                                               {{ $user->belongsToOrganizationGroup($group) ? 'checked' : '' }}
                                               {{ !$user->belongsToOrganization($groups->first()->organization) ? 'disabled' : '' }}
                                               class="mt-1 rounded border-gray-300 text-info-600 shadow-sm focus:border-info-500 focus:ring-info-500 group-checkbox"
                                               data-org-id="{{ $orgId }}">
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $group->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                {{ $group->users->count() }} {{ __('members') }}
                                            </div>
                                            @if($group->description)
                                                <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                                    {{ Str::limit($group->description, 60) }}
                                                </div>
                                            @endif
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-8">
                        <x-icon name="user-group" class="h-12 w-12 text-gray-400 mx-auto mb-3" />
                        <p class="text-gray-500 dark:text-gray-400">{{ __('No organization groups available.') }}</p>
                    </div>
                @endif
                @error('organization_groups')
                    <p class="mt-2 text-sm text-error-600 dark:text-error-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Current Assignments Summary -->
        <div class="bg-success-50 dark:bg-success-900/20 rounded-lg border border-success-200 dark:border-success-800 p-6">
            <h4 class="text-md font-semibold text-success-900 dark:text-success-100 mb-3">{{ __('Current Assignments Summary') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="font-medium text-success-800 dark:text-success-200">{{ __('Organizations:') }}</span>
                    <span class="text-success-700 dark:text-success-300">
                        {{ $user->organizations->count() > 0 ? $user->organizations->pluck('name')->join(', ') : __('None') }}
                    </span>
                </div>
                <div>
                    <span class="font-medium text-success-800 dark:text-success-200">{{ __('Groups:') }}</span>
                    <span class="text-success-700 dark:text-success-300">
                        {{ $user->organizationGroups->count() }} {{ __('groups assigned') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4 pt-6">
            <a href="{{ route('admin.users.show', $user) }}" 
               class="btn btn-secondary">
                {{ __('Cancel') }}
            </a>
            <button type="submit" 
                    class="btn btn-success">
                <x-icon name="office-building" class="h-4 w-4 mr-2" />
                {{ __('Update Organizations & Groups') }}
            </button>
        </div>
    </form>

    <!-- JavaScript for enhanced UX and group filtering -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle organization selection to enable/disable groups
            const orgCheckboxes = document.querySelectorAll('.organization-checkbox');
            
            orgCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const orgId = this.dataset.orgId;
                    const isChecked = this.checked;
                    
                    // Update group availability
                    updateGroupAvailability(orgId, isChecked);
                    
                    // If unchecking organization, uncheck all its groups
                    if (!isChecked) {
                        const groupCheckboxes = document.querySelectorAll(`.group-checkbox[data-org-id="${orgId}"]`);
                        groupCheckboxes.forEach(groupCheckbox => {
                            groupCheckbox.checked = false;
                            groupCheckbox.disabled = true;
                        });
                    }
                });
            });
            
            function updateGroupAvailability(orgId, isAvailable) {
                const groupContainers = document.querySelectorAll(`.group-checkbox-container[data-org-id="${orgId}"]`);
                const groupCheckboxes = document.querySelectorAll(`.group-checkbox[data-org-id="${orgId}"]`);
                const orgStatus = document.querySelector(`.org-status[data-org-id="${orgId}"]`);
                
                groupCheckboxes.forEach(checkbox => {
                    checkbox.disabled = !isAvailable;
                });
                
                groupContainers.forEach(container => {
                    if (isAvailable) {
                        container.classList.remove('opacity-50', 'cursor-not-allowed');
                        container.classList.add('cursor-pointer');
                    } else {
                        container.classList.add('opacity-50', 'cursor-not-allowed');
                        container.classList.remove('cursor-pointer');
                    }
                });
                
                if (orgStatus) {
                    orgStatus.textContent = isAvailable ? '(Available)' : '(Select organization first)';
                }
            }
            
            // Initialize group availability based on current organization selection
            orgCheckboxes.forEach(checkbox => {
                const orgId = checkbox.dataset.orgId;
                const isChecked = checkbox.checked;
                updateGroupAvailability(orgId, isChecked);
            });
            
            // Add visual feedback when checkboxes are changed
            const allCheckboxes = document.querySelectorAll('input[type="checkbox"]');
            allCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.closest('label');
                    if (this.checked && !this.disabled) {
                        label.classList.add('ring-2', 'ring-green-500', 'ring-opacity-50');
                    } else {
                        label.classList.remove('ring-2', 'ring-green-500', 'ring-opacity-50');
                    }
                });
            });
        });
    </script>
</x-layouts.app>
