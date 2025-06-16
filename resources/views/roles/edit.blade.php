<x-layouts.app>
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">{{ __('Edit Role: :name', ['name' => $role->name]) }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ __('Modify role settings and permissions') }}</p>
            </div>
            <a href="{{ route('roles.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                {{ __('Back to Roles') }}
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('roles.update', $role) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <!-- Role Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Role Name') }} <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                id="name" 
                                value="{{ old('name', $role->name) }}"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                                placeholder="Enter role name"
                                required
                            >
                            @error('name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Guard Name -->
                        <div>
                            <label for="guard_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                {{ __('Guard Name') }}
                            </label>
                            <select 
                                name="guard_name" 
                                id="guard_name"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 shadow-sm"
                            >
                                <option value="web" {{ old('guard_name', $role->guard_name) == 'web' ? 'selected' : '' }}>Web</option>
                                <option value="api" {{ old('guard_name', $role->guard_name) == 'api' ? 'selected' : '' }}>API</option>
                            </select>
                            @error('guard_name')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permissions -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                {{ __('Permissions') }}
                            </label>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                @foreach ($permissions as $permission)
                                    <div class="flex items-center">
                                        <input 
                                            type="checkbox" 
                                            name="permissions[]" 
                                            value="{{ $permission->id }}" 
                                            id="permission_{{ $permission->id }}"
                                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                            {{ 
                                                (is_array(old('permissions')) && in_array($permission->id, old('permissions'))) || 
                                                (!old('permissions') && in_array($permission->id, $rolePermissions)) 
                                                ? 'checked' : '' 
                                            }}
                                        >
                                        <label for="permission_{{ $permission->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                            {{ $permission->name }}
                                            @if ($permission->guard_name !== 'web')
                                                <span class="text-xs text-gray-500 dark:text-gray-400">({{ $permission->guard_name }})</span>
                                            @endif
                                        </label>
                                    </div>
                                @endforeach

                                @if ($permissions->isEmpty())
                                    <div class="col-span-full text-center text-gray-500 dark:text-gray-400 py-4">
                                        {{ __('No permissions available.') }}
                                    </div>
                                @endif
                            </div>
                            @error('permissions')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Permission Selection Helpers -->
                        <div class="flex space-x-4">
                            <button 
                                type="button" 
                                onclick="selectAllPermissions()" 
                                class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                            >
                                {{ __('Select All') }}
                            </button>
                            <button 
                                type="button" 
                                onclick="deselectAllPermissions()" 
                                class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300"
                            >
                                {{ __('Deselect All') }}
                            </button>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('roles.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                {{ __('Cancel') }}
                            </a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                {{ __('Update Role') }}
                            </button>
                        </div>                </form>
            </div>
        </div>
    </div>

    <script>
        function selectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = true);
        }

        function deselectAllPermissions() {
            const checkboxes = document.querySelectorAll('input[name="permissions[]"]');
            checkboxes.forEach(checkbox => checkbox.checked = false);
        }
    </script>
</x-layouts.app>
