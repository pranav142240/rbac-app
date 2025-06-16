<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $organization->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Organization Details
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $organization)
                    <a href="{{ route('organizations.edit', $organization) }}" 
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endcan
                <a href="{{ route('organizations.members', $organization) }}" 
                   class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Manage Members
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Organization Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Organization Information</h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $organization->name }}</dd>
                                </div>
                                
                                @if($organization->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $organization->description }}</dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $organization->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $organization->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $organization->created_at->format('M j, Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Contact Information</h3>
                            
                            <dl class="space-y-3">
                                @if($organization->email)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">
                                            <a href="mailto:{{ $organization->email }}" class="hover:underline">
                                                {{ $organization->email }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                @if($organization->phone)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $organization->phone }}</dd>
                                    </div>
                                @endif

                                @if($organization->website)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Website</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">
                                            <a href="{{ $organization->website }}" target="_blank" class="hover:underline">
                                                {{ $organization->website }}
                                            </a>
                                        </dd>
                                    </div>
                                @endif

                                @if($organization->full_address)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $organization->full_address }}</dd>
                                    </div>
                                @endif
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-blue-600">{{ $organization->users->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Members</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-green-600">{{ $organization->organizationGroups->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Total Groups</div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <div class="text-3xl font-bold text-purple-600">{{ $organization->activeGroups->count() }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Active Groups</div>
                    </div>
                </div>
            </div>

            <!-- Recent Members -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Members</h3>
                            <a href="{{ route('organizations.members', $organization) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                View All
                            </a>
                        </div>

                        @if($recentMembers->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentMembers as $member)
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-8 w-8">
                                                <div class="h-8 w-8 rounded-full bg-gray-300 flex items-center justify-center text-xs font-medium">
                                                    {{ $member->initials() }}
                                                </div>
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $member->name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $member->email }}</p>
                                            </div>
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $member->pivot->created_at->diffForHumans() }}
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No members yet.</p>
                        @endif
                    </div>
                </div>

                <!-- Recent Groups -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Recent Groups</h3>
                            <div class="flex space-x-2">
                                <a href="{{ route('organizations.groups.index', $organization) }}" 
                                   class="text-sm text-blue-600 hover:text-blue-800">
                                    View All
                                </a>
                                @can('update', $organization)
                                    <a href="{{ route('organizations.groups.create', $organization) }}" 
                                       class="text-sm text-green-600 hover:text-green-800">
                                        Create Group
                                    </a>
                                @endcan
                            </div>
                        </div>

                        @if($recentGroups->count() > 0)
                            <div class="space-y-3">
                                @foreach($recentGroups as $group)
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $group->name }}</p>
                                            @if($group->description)
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ Str::limit($group->description, 50) }}</p>
                                            @endif
                                        </div>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $group->users_count }} members</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $group->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 text-sm">No groups yet.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
