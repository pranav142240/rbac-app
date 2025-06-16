<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\OrganizationGroup;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrganizationGroupController extends Controller
{
    /**
     * Display a listing of groups for an organization.
     */
    public function index(Organization $organization)
    {
        $this->authorize('view', $organization);

        $groups = $organization->organizationGroups()
                              ->with(['users'])
                              ->withCount('users')
                              ->paginate(15);

        return view('organizations.groups.index', compact('organization', 'groups'));
    }

    /**
     * Show the form for creating a new group.
     */
    public function create(Organization $organization)
    {
        $this->authorize('update', $organization);

        return view('organizations.groups.create', compact('organization'));
    }

    /**
     * Store a newly created group in storage.
     */
    public function store(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organization_groups')->where(function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                }),
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['organization_id'] = $organization->id;
        $validated['is_active'] = $request->has('is_active');

        $group = OrganizationGroup::create($validated);

        return redirect()
            ->route('organizations.groups.show', [$organization, $group])
            ->with('success', 'Organization group created successfully.');
    }

    /**
     * Display the specified group.
     */
    public function show(Organization $organization, OrganizationGroup $organizationGroup)
    {
        $this->authorize('view', $organization);

        $group = $organizationGroup->load(['users', 'organization']);
        
        // Get users who are members of the organization but not this group
        $availableUsers = $organization->users()
                                      ->whereNotIn('users.id', $group->users->pluck('id'))
                                      ->get();

        return view('organizations.groups.show', compact('organization', 'group', 'availableUsers'));
    }

    /**
     * Show the form for editing the specified group.
     */
    public function edit(Organization $organization, OrganizationGroup $organizationGroup)
    {
        $this->authorize('update', $organization);

        return view('organizations.groups.edit', compact('organization', 'organizationGroup'));
    }

    /**
     * Update the specified group in storage.
     */
    public function update(Request $request, Organization $organization, OrganizationGroup $organizationGroup)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organization_groups')->where(function ($query) use ($organization) {
                    return $query->where('organization_id', $organization->id);
                })->ignore($organizationGroup->id),
            ],
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $organizationGroup->update($validated);

        return redirect()
            ->route('organizations.groups.show', [$organization, $organizationGroup])
            ->with('success', 'Organization group updated successfully.');
    }

    /**
     * Remove the specified group from storage.
     */
    public function destroy(Organization $organization, OrganizationGroup $organizationGroup)
    {
        $this->authorize('delete', $organization);

        // Remove all users from the group first
        $organizationGroup->users()->detach();

        $organizationGroup->delete();

        return redirect()
            ->route('organizations.groups.index', $organization)
            ->with('success', 'Organization group deleted successfully.');
    }

    /**
     * Add a user to the group.
     */
    public function addUser(Request $request, Organization $organization, OrganizationGroup $organizationGroup)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Ensure user is member of the organization
        if (!$organization->hasMember($user)) {
            return back()->withErrors(['user_id' => 'User must be a member of the organization first.']);
        }

        $organizationGroup->addMember($user);

        return back()->with('success', 'User added to group successfully.');
    }

    /**
     * Remove a user from the group.
     */
    public function removeUser(Organization $organization, OrganizationGroup $organizationGroup, User $user)
    {
        $this->authorize('update', $organization);

        $organizationGroup->removeMember($user);

        return back()->with('success', 'User removed from group successfully.');
    }

    /**
     * Show a group without requiring organization parameter (standalone view).
     */
    public function showStandalone(OrganizationGroup $organizationGroup)
    {
        $organization = $organizationGroup->organization;
        $this->authorize('view', $organization);

        $group = $organizationGroup->load(['users', 'organization']);
        
        // Get users who are members of the organization but not this group
        $availableUsers = $organization->users()
                                      ->whereNotIn('users.id', $group->users->pluck('id'))
                                      ->get();

        return view('organizations.groups.show', compact('organization', 'group', 'availableUsers'));
    }

    /**
     * Display a listing of all groups the user has access to.
     */    public function indexStandalone()
    {
        $user = Auth::user();
        
        // Get only groups the user actually belongs to
        $groups = OrganizationGroup::with(['organization', 'users'])
                                  ->whereHas('users', function($query) use ($user) {
                                      $query->where('user_id', $user->id);
                                  })
                                  ->withCount('users')
                                  ->paginate(15);

        return view('organization-groups.index', compact('groups'));
    }
}
