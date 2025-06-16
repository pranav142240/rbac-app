<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrgController extends Controller
{
    /**
     * Display a listing of organizations.
     */
    public function index()
    {
        $organizations = Organization::active()
                                   ->with(['users'])
                                   ->withCount(['users', 'organizationGroups'])
                                   ->paginate(15);

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        $this->authorize('create', Organization::class);

        return view('organizations.create');
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Organization::class);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:organizations,name',
            'description' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        DB::transaction(function () use ($validated) {
            $organization = Organization::create($validated);
            
            // Add the creator as the first member
            $organization->addMember(Auth::user());
        });

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization)
    {
        $this->authorize('view', $organization);

        $organization->load(['users', 'organizationGroups.users']);
        
        $recentMembers = $organization->users()
                                    ->orderBy('organization_user.created_at', 'desc')
                                    ->limit(5)
                                    ->get();

        $recentGroups = $organization->activeGroups()
                                   ->withCount('users')
                                   ->orderBy('created_at', 'desc')
                                   ->limit(5)
                                   ->get();

        return view('organizations.show', compact('organization', 'recentMembers', 'recentGroups'));
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit(Organization $organization)
    {
        $this->authorize('update', $organization);

        return view('organizations.edit', compact('organization'));
    }

    /**
     * Update the specified organization in storage.
     */
    public function update(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('organizations', 'name')->ignore($organization->id),
            ],
            'description' => 'nullable|string|max:1000',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $organization->update($validated);

        return redirect()
            ->route('organizations.show', $organization)
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization from storage.
     */
    public function destroy(Organization $organization)
    {
        $this->authorize('delete', $organization);

        DB::transaction(function () use ($organization) {
            // Remove all users from organization groups
            foreach ($organization->organizationGroups as $group) {
                $group->users()->detach();
            }
            
            // Remove all users from organization
            $organization->users()->detach();
            
            // Delete the organization (soft delete)
            $organization->delete();
        });

        return redirect()
            ->route('organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    /**
     * Show members of the organization.
     */
    public function members(Organization $organization)
    {
        $this->authorize('view', $organization);

        $members = $organization->users()
                               ->withPivot('joined_at')
                               ->paginate(15);

        // Get users who are not members of this organization
        $availableUsers = User::whereNotIn('id', $organization->users->pluck('id'))
                            ->orderBy('name')
                            ->get();

        return view('organizations.members', compact('organization', 'members', 'availableUsers'));
    }

    /**
     * Add a user to the organization.
     */
    public function addMember(Request $request, Organization $organization)
    {
        $this->authorize('update', $organization);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($validated['user_id']);

        if ($organization->hasMember($user)) {
            return back()->withErrors(['user_id' => 'User is already a member of this organization.']);
        }

        $organization->addMember($user);

        return back()->with('success', 'User added to organization successfully.');
    }

    /**
     * Remove a user from the organization.
     */
    public function removeMember(Organization $organization, User $user)
    {
        $this->authorize('update', $organization);

        $organization->removeMember($user);

        return back()->with('success', 'User removed from organization successfully.');
    }
}
