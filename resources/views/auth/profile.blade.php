<x-layouts.app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">{{ __('Profile & Security') }}</h1>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Manage your profile information and authentication methods.') }}
                </p>
            </div>

            <div class="space-y-6">
            
            <!-- Profile Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Profile Information</h3>
                    
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="primary_auth_method" :value="__('Primary Authentication Method')" />
                                <select id="primary_auth_method" name="primary_auth_method" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    @foreach($authMethods as $method)
                                        <option value="{{ $method->auth_method_type }}" {{ $user->primary_auth_method === $method->auth_method_type ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' + ', $method->auth_method_type)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 mt-6">
                            <x-primary-button>{{ __('Save') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Authentication Methods -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Authentication Methods</h3>
                        <button type="button" onclick="openAddMethodModal()" class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md text-sm">
                            Add Method
                        </button>
                    </div>

                    <div class="space-y-4">
                        @foreach($authMethods->groupBy('auth_method_type') as $type => $methods)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900 dark:text-gray-100">
                                            {{ ucfirst(str_replace('_', ' + ', $type)) }}
                                            @if($user->primary_auth_method === $type)
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 ml-2">
                                                    Primary
                                                </span>
                                            @endif
                                        </h4>
                                        @foreach($methods as $method)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $method->identifier }}
                                                @if($method->isVerified())
                                                    <span class="text-green-600 ml-2">✓ Verified</span>
                                                @else
                                                    <span class="text-yellow-600 ml-2">⚠ Unverified</span>
                                                @endif
                                            </p>
                                        @endforeach
                                    </div>
                                    <div class="flex space-x-2">
                                        @if(!$methods->first()->isVerified())
                                            <button type="button" onclick="verifyMethod({{ $methods->first()->id }})" class="text-blue-600 hover:text-blue-800 text-sm">
                                                Verify
                                            </button>
                                        @endif
                                        @if($authMethods->count() > 1)
                                            <button type="button" onclick="removeMethod({{ $methods->first()->id }})" class="text-red-600 hover:text-red-800 text-sm">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Roles and Permissions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Roles & Permissions</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Your Roles</h4>
                            <div class="space-y-2">
                                @forelse($roles as $role)
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        {{ $role }}
                                    </span>
                                @empty
                                    <p class="text-sm text-gray-600 dark:text-gray-400">No roles assigned</p>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Your Permissions</h4>
                            <div class="max-h-48 overflow-y-auto space-y-1">
                                @forelse($permissions as $permission)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 bg-gray-50 dark:bg-gray-700 px-2 py-1 rounded">
                                        {{ $permission->name }}
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-600 dark:text-gray-400">No permissions assigned</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Method Modal -->
    <div id="addMethodModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="addMethodForm">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Authentication Method</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="new_auth_method_type" :value="__('Method Type')" />
                                <select id="new_auth_method_type" name="auth_method_type" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                    <option value="">Select method</option>
                                    <option value="email_password">Email + Password</option>
                                    <option value="email_otp">Email + OTP</option>
                                    <option value="phone_password">Phone + Password</option>
                                    <option value="phone_otp">Phone + OTP</option>
                                    <option value="google_sso">Google SSO</option>
                                </select>
                            </div>

                            <div id="new_identifier_section" class="hidden">
                                <x-input-label for="new_identifier" :value="__('Email/Phone')" />
                                <x-text-input id="new_identifier" name="identifier" type="text" class="mt-1 block w-full" />
                            </div>

                            <div id="new_password_section" class="hidden">
                                <x-input-label for="new_password" :value="__('Password')" />
                                <x-text-input id="new_password" name="password" type="password" class="mt-1 block w-full" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button type="submit" class="sm:ml-3">
                            Add Method
                        </x-primary-button>
                        <button type="button" onclick="closeAddMethodModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openAddMethodModal() {
            document.getElementById('addMethodModal').classList.remove('hidden');
        }

        function closeAddMethodModal() {
            document.getElementById('addMethodModal').classList.add('hidden');
            document.getElementById('addMethodForm').reset();
        }

        // Handle add method form submission
        document.getElementById('addMethodForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("auth.add-method") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert('Authentication method added successfully!');
                    location.reload();
                } else {
                    alert('Failed to add authentication method');
                }
            })
            .catch(error => {
                alert('Error adding authentication method');
            });
        });

        // Dynamic form fields based on method type
        document.getElementById('new_auth_method_type').addEventListener('change', function() {
            const methodType = this.value;
            const identifierSection = document.getElementById('new_identifier_section');
            const passwordSection = document.getElementById('new_password_section');
            const identifierInput = document.getElementById('new_identifier');
            const identifierLabel = identifierSection.querySelector('label');

            // Reset sections
            identifierSection.classList.add('hidden');
            passwordSection.classList.add('hidden');

            if (methodType && methodType !== 'google_sso') {
                identifierSection.classList.remove('hidden');
                
                // Update label and input type
                if (methodType.includes('email')) {
                    identifierLabel.textContent = 'Email Address';
                    identifierInput.type = 'email';
                    identifierInput.placeholder = 'Enter email address';
                } else if (methodType.includes('phone')) {
                    identifierLabel.textContent = 'Phone Number';
                    identifierInput.type = 'tel';
                    identifierInput.placeholder = 'Enter phone number';
                }

                // Show password field for password methods
                if (methodType.includes('password')) {
                    passwordSection.classList.remove('hidden');
                }
            }
        });

        function verifyMethod(methodId) {
            // Implementation for verifying auth method
            alert('Verification feature will be implemented');
        }        function removeMethod(methodId) {
            if (confirm('Are you sure you want to remove this authentication method?')) {
                // Implementation for removing auth method
                alert('Remove method feature will be implemented');
            }
        }
    </script>
            </div>
        </div>
    </div>
</x-layouts.app>
