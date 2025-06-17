<x-layouts.app>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">{{ __('Profile & Security') }}</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('Manage your profile information and authentication methods.') }}
                </p>
            </div>

            <!-- Success Messages -->
            @if (session('success'))
                <div class="rounded-md bg-success-50 dark:bg-success-900 p-4 mb-6">
                    <div class="text-sm text-success-700 dark:text-success-200">
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="rounded-md bg-error-50 dark:bg-error-900 p-4 mb-6">
                    <div class="text-sm text-error-700 dark:text-error-200">
                        <div class="font-medium">{{ __('Please fix the following errors:') }}</div>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="space-y-6">
            
            <!-- Profile Information -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Profile Information</h3>
                    
                    <form method="POST" action="{{ route('auth.update-profile') }}">
                        @csrf
                        @method('patch')

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" name="name" type="text" 
                                             placeholder="Enter your full name"
                                             class="mt-1 block w-full" :value="old('name', $user->name)" required />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div>
                                <x-input-label for="email" :value="__('Email Address')" />
                                <x-text-input id="email" name="email" type="email" 
                                             placeholder="Enter your email address"
                                             class="mt-1 block w-full" :value="old('email', $user->email)" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank if you don't want to update</p>
                            </div>

                            <div>
                                <x-input-label for="phone" :value="__('Phone Number')" />
                                <x-text-input id="phone" name="phone" type="tel" 
                                             placeholder="Enter your phone number (e.g., +1234567890)"
                                             class="mt-1 block w-full" :value="old('phone', $user->phone)" />
                                <x-input-error class="mt-2" :messages="$errors->get('phone')" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Leave blank if you don't want to update</p>
                            </div>

                            <div>
                                <x-input-label for="primary_auth_method" :value="__('Primary Authentication Method')" />
                                <select id="primary_auth_method" name="primary_auth_method" class="form-input">
                                    @foreach($authMethods->groupBy('auth_method_type') as $type => $methods)
                                        @if($methods->first()->isVerified())
                                            <option value="{{ $type }}" {{ $user->primary_auth_method === $type ? 'selected' : '' }}>
                                                {{ ucfirst(str_replace('_', ' + ', $type)) }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Only verified authentication methods can be set as primary</p>
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
                        <button type="button" onclick="openAddMethodModal()" class="bg-primary-600 hover:bg-primary-700 dark:bg-primary-500 dark:hover:bg-primary-600 text-white px-4 py-2 rounded-md text-sm transition-colors duration-200">
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
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 ml-2">
                                                    Primary
                                                </span>
                                            @endif
                                        </h4>
                                        @foreach($methods as $method)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                {{ $method->identifier }}
                                                @if($method->isVerified())
                                                    <span class="text-green-600 dark:text-green-400 ml-2">✓ Verified</span>
                                                @else
                                                    <span class="text-yellow-600 dark:text-yellow-400 ml-2">⚠ Unverified</span>
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
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
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

            <!-- Organizations & Groups -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Organizations & Groups</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Your Organizations</h4>
                            <div class="space-y-2">
                                @forelse($user->organizations as $organization)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $organization->name }}</span>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $organization->description }}</p>
                                        </div>
                                        <a href="{{ route('organizations.show', $organization) }}" 
                                           class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-xs">
                                            View
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Not a member of any organizations</p>
                                    <a href="{{ route('organizations.index') }}" 
                                       class="inline-flex items-center px-3 py-1 border border-primary-300 dark:border-primary-600 text-xs font-medium rounded-md text-primary-600 dark:text-primary-400 bg-white dark:bg-gray-700 hover:bg-primary-50 dark:hover:bg-gray-600">
                                        Explore Organizations
                                    </a>
                                @endforelse
                            </div>
                        </div>

                        <div>
                            <h4 class="font-medium text-gray-900 dark:text-gray-100 mb-2">Your Groups</h4>
                            <div class="space-y-2">
                                @forelse($user->organizationGroups as $group)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div>
                                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $group->name }}</span>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">{{ $group->organization->name }}</p>
                                        </div>
                                        <a href="{{ route('organization-groups.show', $group) }}" 
                                           class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-xs">
                                            View
                                        </a>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Not a member of any groups</p>
                                    <a href="{{ route('organization-groups.index') }}" 
                                       class="inline-flex items-center px-3 py-1 border border-primary-300 dark:border-primary-600 text-xs font-medium rounded-md text-primary-600 dark:text-primary-400 bg-white dark:bg-gray-700 hover:bg-primary-50 dark:hover:bg-gray-600">
                                        Explore Groups
                                    </a>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Authentication Settings -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Authentication Settings</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">Primary Login Method</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Currently: <span class="font-medium">{{ ucfirst(str_replace('_', ' + ', $user->primary_auth_method)) }}</span>
                                </p>
                            </div>
                            <button type="button" onclick="showPrimaryMethodModal()" 
                                    class="text-primary-600 hover:text-primary-800 dark:text-primary-400 dark:hover:text-primary-300 text-sm">
                                Change
                            </button>
                        </div>

                        <div class="flex items-center justify-between p-4 border border-gray-200 dark:border-gray-700 rounded-lg">
                            <div>
                                <h4 class="font-medium text-gray-900 dark:text-gray-100">Account Security</h4>
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $authMethods->where('auth_method_verified_at', '!=', null)->count() }} of {{ $authMethods->count() }} methods verified
                                </p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $authMethods->where('auth_method_verified_at', '!=', null)->count() === $authMethods->count() ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200' }}">
                                {{ $authMethods->where('auth_method_verified_at', '!=', null)->count() === $authMethods->count() ? 'Secure' : 'Needs Attention' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Method Modal -->
    <div id="addMethodModal" class="fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="addMethodForm">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Authentication Method</h3>
                        
                        <div class="space-y-4">
                            <div>
                                <x-input-label for="new_auth_method_type" :value="__('Method Type')" />
                                <select id="new_auth_method_type" name="auth_method_type" class="form-input" required>
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
                                <x-text-input id="new_identifier" name="identifier" type="text" 
                                             placeholder="Enter email or phone number"
                                             class="mt-1 block w-full" 
                                             required />
                            </div>

                            <div id="new_password_section" class="hidden">
                                <x-input-label for="new_password" :value="__('Password')" />
                                <x-text-input id="new_password" name="password" type="password" 
                                             placeholder="Enter password (min 8 characters)"
                                             class="mt-1 block w-full" 
                                             minlength="8" />
                            </div>
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button type="submit" class="sm:ml-3">
                            Add Method
                        </x-primary-button>
                        <button type="button" onclick="closeAddMethodModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Primary Method Modal -->
    <div id="primaryMethodModal" class="fixed inset-0 bg-gray-600 dark:bg-gray-900 bg-opacity-50 dark:bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="primaryMethodForm">
                    @csrf
                    <div class="px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Change Primary Authentication Method</h3>
                        
                        <div class="space-y-3">
                            @foreach($authMethods->where('auth_method_verified_at', '!=', null)->groupBy('auth_method_type') as $type => $methods)
                                <label class="flex items-center p-3 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <input type="radio" name="primary_auth_method" value="{{ $type }}" 
                                           {{ $user->primary_auth_method === $type ? 'checked' : '' }}
                                           class="h-4 w-4 text-primary-600 focus:ring-primary-500 border-gray-300">
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ ucfirst(str_replace('_', ' + ', $type)) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $methods->first()->identifier }}
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <x-primary-button type="submit" class="sm:ml-3">
                            Update Primary Method
                        </x-primary-button>
                        <button type="button" onclick="closePrimaryMethodModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
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
            
            // Reset form and hide all dynamic sections
            const form = document.getElementById('addMethodForm');
            form.reset();
            
            // Hide identifier and password sections
            document.getElementById('new_identifier_section').classList.add('hidden');
            document.getElementById('new_password_section').classList.add('hidden');
            
            // Remove required attributes
            document.getElementById('new_identifier').removeAttribute('required');
            document.getElementById('new_password').removeAttribute('required');
        }

        // Handle add method form submission
        document.getElementById('addMethodForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            const methodType = formData.get('auth_method_type');
            
            console.log('Form submission started', {
                methodType: methodType,
                identifier: formData.get('identifier'),
                hasPassword: !!formData.get('password')
            });
            
            // Validate required fields
            if (!methodType) {
                alert('Please select an authentication method');
                return;
            }
            
            if (methodType !== 'google_sso') {
                const identifier = formData.get('identifier');
                if (!identifier || identifier.trim() === '') {
                    alert('Please enter your email or phone number');
                    return;
                }
                
                if (methodType.includes('password')) {
                    const password = formData.get('password');
                    if (!password || password.trim() === '') {
                        alert('Please enter a password');
                        return;
                    }
                    if (password.length < 8) {
                        alert('Password must be at least 8 characters long');
                        return;
                    }
                }
            }
            
            // Handle Google SSO differently
            if (methodType === 'google_sso') {
                // Redirect to Google OAuth
                window.location.href = '{{ route("auth.google") }}';
                return;
            }
            
            // Show loading state
            const submitButton = this.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Adding...';
            
            // Check if CSRF token exists
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                console.error('CSRF token not found');
                alert('Security token not found. Please refresh the page and try again.');
                submitButton.disabled = false;
                submitButton.textContent = originalText;
                return;
            }
            
            console.log('Making fetch request to:', '{{ route("auth.add-method") }}');
            
            fetch('{{ route("auth.add-method") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                
                if (!response.ok) {
                    return response.json().then(data => {
                        console.error('Server error:', data);
                        return Promise.reject(data);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Success response:', data);
                if (data.message) {
                    alert('Authentication method added successfully!');
                    closeAddMethodModal();
                    location.reload();
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Request failed:', error);
                if (error.errors) {
                    let errorMsg = 'Validation errors:\n';
                    for (let field in error.errors) {
                        errorMsg += '• ' + error.errors[field].join('\n• ') + '\n';
                    }
                    alert(errorMsg);
                } else if (error.error) {
                    alert('Error: ' + error.error);
                } else if (error.message) {
                    alert('Error: ' + error.message);
                } else {
                    alert('Error adding authentication method. Please check the console for details and try again.');
                }
            })
            .finally(() => {
                // Reset button state
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            });
        });

        // Dynamic form fields based on method type
        document.getElementById('new_auth_method_type').addEventListener('change', function() {
            const methodType = this.value;
            const identifierSection = document.getElementById('new_identifier_section');
            const passwordSection = document.getElementById('new_password_section');
            const identifierInput = document.getElementById('new_identifier');
            const passwordInput = document.getElementById('new_password');
            const identifierLabel = identifierSection.querySelector('label');

            // Reset sections and remove required attributes
            identifierSection.classList.add('hidden');
            passwordSection.classList.add('hidden');
            identifierInput.removeAttribute('required');
            passwordInput.removeAttribute('required');

            if (methodType && methodType !== 'google_sso') {
                identifierSection.classList.remove('hidden');
                identifierInput.setAttribute('required', 'required');
                
                // Update label and input type
                if (methodType.includes('email')) {
                    identifierLabel.textContent = 'Email Address';
                    identifierInput.type = 'email';
                    identifierInput.placeholder = 'Enter email address';
                } else if (methodType.includes('phone')) {
                    identifierLabel.textContent = 'Phone Number';
                    identifierInput.type = 'tel';
                    identifierInput.placeholder = 'Enter phone number (e.g., +1234567890)';
                }

                // Show password field for password methods
                if (methodType.includes('password')) {
                    passwordSection.classList.remove('hidden');
                    passwordInput.setAttribute('required', 'required');
                }
            }
        });

        function verifyMethod(methodId) {
            // Show loading state
            const button = event.target;
            const originalText = button.textContent;
            button.disabled = true;
            button.textContent = 'Sending...';

            fetch(`{{ url('auth/verify-method') }}/${methodId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    if (data.message.includes('verification sent') || data.message.includes('verified successfully')) {
                        // Refresh page to show updated status
                        location.reload();
                    }
                } else if (data.error) {
                    alert('Error: ' + data.error);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Failed to verify authentication method');
            })
            .finally(() => {
                button.disabled = false;
                button.textContent = originalText;
            });
        }

        function removeMethod(methodId) {
            if (confirm('Are you sure you want to remove this authentication method?')) {
                fetch(`{{ url('auth/remove-method') }}/${methodId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.message) {
                        alert(data.message);
                        location.reload();
                    } else if (data.error) {
                        alert('Error: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Failed to remove authentication method');
                });
            }
        }

        function showPrimaryMethodModal() {
            document.getElementById('primaryMethodModal').classList.remove('hidden');
        }

        function closePrimaryMethodModal() {
            document.getElementById('primaryMethodModal').classList.add('hidden');
        }

        // Handle primary method form submission
        document.getElementById('primaryMethodForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('{{ route("auth.set-primary-method") }}', {
                method: 'PATCH',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert('Primary authentication method updated successfully!');
                    closePrimaryMethodModal();
                    location.reload();
                } else if (data.error) {
                    alert('Error: ' + data.error);
                } else if (data.errors) {
                    let errorMsg = 'Validation errors:\n';
                    for (let field in data.errors) {
                        errorMsg += data.errors[field].join('\n') + '\n';
                    }
                    alert(errorMsg);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error updating primary authentication method');
            });
        });
    </script>
            </div>
        </div>
    </div>
</x-layouts.app>
