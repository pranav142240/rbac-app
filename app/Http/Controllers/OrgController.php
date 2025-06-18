<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Org\StoreOrgRequest;
use App\Http\Requests\Org\UpdateOrgRequest;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrgController extends Controller
{    /**
     * Display a listing of organizations.
     */
    public function index(Request $request)
    {
        $organizations = Organization::accessibleByUser(Auth::user())
                                   ->with(['users'])
                                   ->withCount(['users', 'organizationGroups'])
                                   ->when($request->search, function ($query, $search) {
                                       return $query->where('name', 'like', "%{$search}%")
                                                  ->orWhere('description', 'like', "%{$search}%");
                                   })
                                   ->when($request->status, function ($query, $status) {
                                       return $query->where('is_active', $status === 'active');
                                   })
                                   ->latest()
                                   ->paginate(15)
                                   ->withQueryString();

        return view('organizations.index', compact('organizations'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create()
    {
        $this->authorize('create', Organization::class);

        return view('organizations.create');
    }    /**
     * Store a newly created organization in storage.
     */
    public function store(StoreOrgRequest $request)
    {
        $validated = $request->validated();
        
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
    }    /**
     * Update the specified organization in storage.
     */
    public function update(UpdateOrgRequest $request, Organization $organization)
    {
        $validated = $request->validated();
        
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

    /**
     * Toggle organization status (active/inactive).
     */
    public function toggleStatus(Organization $organization)
    {
        $this->authorize('update', $organization);

        $organization->update([
            'is_active' => !$organization->is_active
        ]);

        $status = $organization->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Organization {$status} successfully.");
    }
}
