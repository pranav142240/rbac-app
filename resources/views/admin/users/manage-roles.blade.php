<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Manage User Roles & Memberships') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Assign or remove roles, organizations, and groups for') }} <strong>{{ $user->name }}</strong></p>
            </div>
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                    <x-icon name="eye" class="h-4 w-4 mr-2" />
                    {{ __('View User') }}
                </a>
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                    <x-icon name="list" class="h-4 w-4 mr-2" />
                    {{ __('Back to Users') }}
                </a>
            </div>
        </div>
    </div>

    <!-- User Info Card -->
    <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 p-6 mb-6">
        <div class="flex items-center">
            <div class="w-12 h-12 bg-primary-100 dark:bg-primary-900 rounded-full flex items-center justify-center mr-4">
                <span class="text-primary-600 dark:text-primary-400 font-bold text-lg">
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

    <form method="POST" action="{{ route('admin.users.update-roles', $user) }}" class="space-y-6">
        @csrf

        <!-- Roles Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Assign Roles') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Select the roles this user should have') }}</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($roles as $role)
                        <label class="flex items-start p-4 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ $user->hasRole($role->name) ? 'bg-primary-50 dark:bg-primary-900/20 border-primary-300 dark:border-primary-600' : '' }}">
                            <input type="checkbox" name="roles[]" value="{{ $role->id }}" 
                                   {{ $user->hasRole($role->name) ? 'checked' : '' }}
                                   class="mt-1 rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $role->permissions->count() }} {{ __('permissions') }}
                                </div>
                                @if($role->permissions->count() > 0)
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        {{ $role->permissions->take(3)->pluck('name')->join(', ') }}
                                        @if($role->permissions->count() > 3)
                                            {{ __('and') }} {{ $role->permissions->count() - 3 }} {{ __('more...') }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

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
                                   class="mt-1 rounded border-gray-300 text-success-600 shadow-sm focus:border-success-500 focus:ring-success-500">
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
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Organization Groups Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Group Memberships') }}</h3>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Select which groups this user belongs to') }}</p>
            </div>
            <div class="p-6">
                @if($organizationGroups->count() > 0)
                    @foreach($organizationGroups->groupBy('organization.name') as $orgName => $groups)
                        <div class="mb-6">
                            <h4 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-3 flex items-center">
                                <x-icon name="office-building" class="h-4 w-4 mr-2" />
                                {{ $orgName }}
                            </h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($groups as $group)
                                    <label class="flex items-start p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition-colors duration-200 {{ $user->belongsToOrganizationGroup($group) ? 'bg-info-50 dark:bg-info-900/20 border-info-300 dark:border-info-600' : '' }}">
                                        <input type="checkbox" name="organization_groups[]" value="{{ $group->id }}" 
                                               {{ $user->belongsToOrganizationGroup($group) ? 'checked' : '' }}
                                               class="mt-1 rounded border-gray-300 text-info-600 shadow-sm focus:border-info-500 focus:ring-info-500">
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
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Current Assignments Summary -->
        <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800 p-6">
            <h4 class="text-md font-semibold text-primary-900 dark:text-primary-100 mb-3">{{ __('Current Assignments Summary') }}</h4>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                <div>
                    <span class="font-medium text-primary-800 dark:text-primary-200">{{ __('Roles:') }}</span>
                    <span class="text-primary-700 dark:text-primary-300">
                        {{ $user->roles->count() > 0 ? $user->roles->pluck('name')->join(', ') : __('None') }}
                    </span>
                </div>
                <div>
                    <span class="font-medium text-primary-800 dark:text-primary-200">{{ __('Organizations:') }}</span>
                    <span class="text-primary-700 dark:text-primary-300">
                        {{ $user->organizations->count() > 0 ? $user->organizations->pluck('name')->join(', ') : __('None') }}
                    </span>
                </div>
                <div>
                    <span class="font-medium text-primary-800 dark:text-primary-200">{{ __('Groups:') }}</span>
                    <span class="text-primary-700 dark:text-primary-300">
                        {{ $user->organizationGroups->count() }} {{ __('groups assigned') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4 pt-6">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <x-icon name="shield" class="h-4 w-4 mr-2" />
                {{ __('Update Roles & Memberships') }}
            </button>
        </div>
    </form>

    <!-- JavaScript for enhanced UX -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add visual feedback when checkboxes are changed
            const checkboxes = document.querySelectorAll('input[type="checkbox"]');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    const label = this.closest('label');
                    if (this.checked) {
                        label.classList.add('ring-2', 'ring-primary-500', 'ring-opacity-50');
                    } else {
                        label.classList.remove('ring-2', 'ring-primary-500', 'ring-opacity-50');
                    }
                });
            });
        });
    </script>
</x-layouts.app>
