<x-layouts.app>
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Manage User Roles') }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Assign or remove roles for') }} <strong>{{ $user->name }}</strong></p>
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
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('User Roles') }}</h3>
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

        <!-- Current Roles Summary -->
        <div class="bg-primary-50 dark:bg-primary-900/20 rounded-lg border border-primary-200 dark:border-primary-800 p-6">
            <h4 class="text-md font-semibold text-primary-900 dark:text-primary-100 mb-3">{{ __('Current Roles') }}</h4>
            <div class="text-sm">
                <span class="font-medium text-primary-800 dark:text-primary-200">{{ __('Assigned Roles:') }}</span>
                <span class="text-primary-700 dark:text-primary-300">
                    {{ $user->roles->count() > 0 ? $user->roles->pluck('name')->join(', ') : __('None') }}
                </span>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-end space-x-4 pt-6">
            <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">
                {{ __('Cancel') }}
            </a>
            <button type="submit" class="btn btn-primary">
                <x-icon name="shield" class="h-4 w-4 mr-2" />
                {{ __('Update Roles') }}
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
