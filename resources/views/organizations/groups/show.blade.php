<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $group->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    {{ $organization->name }} - Group Details
                </p>
            </div>
            <div class="flex space-x-2">
                @can('update', $organization)
                    <a href="{{ route('organizations.groups.edit', [$organization, $group]) }}" 
                       class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                        Edit Group
                    </a>
                @endcan
                <a href="{{ route('organizations.groups.index', $organization) }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Back to Groups
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

            @if($errors->any())
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Group Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Group Information</h3>
                            
                            <dl class="space-y-3">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $group->name }}</dd>
                                </div>
                                
                                @if($group->description)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                                        <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $group->description }}</dd>
                                    </div>
                                @endif

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                                    <dd>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $group->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $group->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Organization</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('organizations.show', $organization) }}" class="hover:underline">
                                            {{ $organization->name }}
                                        </a>
                                    </dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $group->created_at->format('M j, Y') }}</dd>
                                </div>

                                <div>
                                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Members</dt>
                                    <dd class="text-sm text-gray-900 dark:text-gray-100">{{ $group->users->count() }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Add Member Form -->
                        @can('update', $organization)
                            @if($availableUsers->count() > 0)
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Member</h3>
                                    <form method="POST" action="{{ route('organizations.groups.users.add', [$organization, $group]) }}">
                                        @csrf
                                        <div class="space-y-4">
                                            <div>
                                                <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                                    Select User
                                                </label>
                                                <select name="user_id" id="user_id" required
                                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                                                    <option value="">Choose a user...</option>
                                                    @foreach($availableUsers as $user)
                                                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <button type="submit" 
                                                    class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                                Add to Group
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @else
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Add Member</h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">All organization members are already in this group.</p>
                                </div>
                            @endif
                        @endcan
                    </div>
                </div>
            </div>

            <!-- Members List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Group Members</h3>

                    @if($group->users->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Member
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                            Joined Group
                                        </th>
                                        @can('update', $organization)
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($group->users as $member)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <div class="flex-shrink-0 h-10 w-10">
                                                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center text-sm font-medium">
                                                            {{ $member->initials() }}
                                                        </div>
                                                    </div>
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                            {{ $member->name }}
                                                        </div>
                                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                                            {{ $member->email }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                                {{ $member->pivot->joined_at ? \Carbon\Carbon::parse($member->pivot->joined_at)->format('M j, Y') : 'N/A' }}
                                            </td>
                                            @can('update', $organization)
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <form method="POST" action="{{ route('organizations.groups.users.remove', [$organization, $group, $member]) }}" 
                                                          onsubmit="return confirm('Are you sure you want to remove this member from the group?')" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                            Remove
                                                        </button>
                                                    </form>
                                                </td>
                                            @endcan
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No members in this group</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding members to this group.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
