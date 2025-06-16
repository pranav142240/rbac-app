<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Organization\StoreOrganizationRequest;
use App\Http\Requests\Organization\UpdateOrganizationRequest;
use App\Models\Organization;
use App\Models\OrganizationGroup;
use App\Models\User;
use App\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class OrganizationController extends Controller
{
    private PermissionService $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    /**
     * Display a listing of organizations.
     */
    public function index(Request $request): View
    {
        $organizations = Organization::with(['organizationGroup', 'users'])
            ->withCount('users')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('code', 'like', "%{$search}%");
            })
            ->when($request->group, function ($query, $group) {
                return $query->where('organization_group_id', $group);
            })
            ->when($request->status, function ($query, $status) {
                return $query->where('is_active', $status === 'active');
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $organizationGroups = OrganizationGroup::all();

        return view('organizations.index', compact('organizations', 'organizationGroups'));
    }

    /**
     * Show the form for creating a new organization.
     */
    public function create(): View
    {
        $organizationGroups = OrganizationGroup::where('is_active', true)->get();
        
        return view('organizations.create', compact('organizationGroups'));
    }

    /**
     * Store a newly created organization in storage.
     */
    public function store(StoreOrganizationRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        $organization = Organization::create($validated);

        return redirect()->route('organizations.index')
            ->with('success', 'Organization created successfully.');
    }

    /**
     * Display the specified organization.
     */
    public function show(Organization $organization): View
    {
        $organization->load(['organizationGroup', 'users.roles']);
        
        $userStats = [
            'total_users' => $organization->users()->count(),
            'active_users' => $organization->users()->where('is_active', true)->count(),
            'admin_users' => $organization->users()->whereHas('roles', function ($query) {
                $query->whereIn('name', ['Super Admin', 'Admin']);
            })->count()
        ];

        $recentUsers = $organization->users()
            ->with('roles')
            ->latest()
            ->limit(5)
            ->get();

        return view('organizations.show', compact('organization', 'userStats', 'recentUsers'));
    }

    /**
     * Show the form for editing the specified organization.
     */
    public function edit(Organization $organization): View
    {
        $organizationGroups = OrganizationGroup::where('is_active', true)->get();
        
        return view('organizations.edit', compact('organization', 'organizationGroups'));
    }

    /**
     * Update the specified organization in storage.
     */
    public function update(UpdateOrganizationRequest $request, Organization $organization): RedirectResponse
    {
        $validated = $request->validated();
        
        $organization->update($validated);

        return redirect()->route('organizations.index')
            ->with('success', 'Organization updated successfully.');
    }

    /**
     * Remove the specified organization from storage.
     */
    public function destroy(Organization $organization): RedirectResponse
    {
        // Check if organization has users
        if ($organization->users()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete organization that has users. Please reassign users first.');
        }

        $organization->delete();

        return redirect()->route('organizations.index')
            ->with('success', 'Organization deleted successfully.');
    }

    /**
     * Toggle organization status (active/inactive).
     */
    public function toggleStatus(Organization $organization): RedirectResponse
    {
        $organization->update([
            'is_active' => !$organization->is_active
        ]);

        $status = $organization->is_active ? 'activated' : 'deactivated';

        return redirect()->back()
            ->with('success', "Organization {$status} successfully.");
    }

    /**
     * Show users belonging to the organization.
     */
    public function users(Organization $organization, Request $request): View
    {
        $users = $organization->users()
            ->with('roles')
            ->when($request->search, function ($query, $search) {
                return $query->where('name', 'like', "%{$search}%")
                           ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($request->role, function ($query, $role) {
                return $query->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                });
            })
            ->paginate(15)
            ->withQueryString();

        return view('organizations.users', compact('organization', 'users'));
    }

    /**
     * Assign user to organization.
     */
    public function assignUser(Request $request, Organization $organization): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $user = User::findOrFail($request->user_id);

        if ($organization->users()->where('user_id', $user->id)->exists()) {
            return redirect()->back()
                ->with('error', 'User is already assigned to this organization.');
        }

        $organization->users()->attach($user->id);

        return redirect()->back()
            ->with('success', 'User assigned to organization successfully.');
    }

    /**
     * Remove user from organization.
     */
    public function removeUser(Request $request, Organization $organization): RedirectResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $organization->users()->detach($request->user_id);

        return redirect()->back()
            ->with('success', 'User removed from organization successfully.');
    }
}
