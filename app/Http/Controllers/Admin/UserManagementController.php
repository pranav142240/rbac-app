<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Organization;
use App\Models\OrganizationGroup;
use App\Models\UserAuthMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;

class UserManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'permission:view_users'])->only(['index', 'show']);
        $this->middleware(['auth', 'permission:create_users'])->only(['create', 'store']);
        $this->middleware(['auth', 'permission:update_users'])->only(['edit', 'update']);
        $this->middleware(['auth', 'permission:delete_users'])->only(['destroy']);
        $this->middleware(['auth', 'permission:manage_user_permissions'])->only(['manageRoles', 'updateRoles']);
    }

    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::with(['roles', 'organizations', 'organizationGroups']);

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->role($request->role);
        }

        // Filter by organization
        if ($request->filled('organization')) {
            $query->whereHas('organizations', function($q) use ($request) {
                $q->where('organizations.id', $request->organization);
            });
        }

        $users = $query->paginate(15)->withQueryString();

        $roles = Role::all();
        $organizations = Organization::all();

        return view('admin.users.index', compact('users', 'roles', 'organizations'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        $roles = Role::all();
        $organizations = Organization::all();
        $organizationGroups = OrganizationGroup::with('organization')->get();

        return view('admin.users.create', compact('roles', 'organizations', 'organizationGroups'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'primary_auth_method' => 'required|in:email_password,phone_password,email_otp,phone_otp',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'organizations' => 'array',
            'organizations.*' => 'exists:organizations,id',
            'organization_groups' => 'array',
            'organization_groups.*' => 'exists:organization_groups,id',
        ]);

        // Create user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'password' => Hash::make($validated['password']),
            'primary_auth_method' => $validated['primary_auth_method'],
            'email_verified_at' => now(),
            'phone_verified_at' => $validated['phone'] ? now() : null,
        ]);

        // Create auth method
        $identifier = $validated['primary_auth_method'] === 'phone_password' || $validated['primary_auth_method'] === 'phone_otp' 
                     ? $validated['phone'] 
                     : $validated['email'];

        UserAuthMethod::create([
            'user_id' => $user->id,
            'auth_method_type' => $validated['primary_auth_method'],
            'identifier' => $identifier,
            'is_active' => true,
            'auth_method_verified_at' => now(),
        ]);

        // Assign roles
        if (!empty($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $user->assignRole($roles);
        }

        // Add to organizations
        if (!empty($validated['organizations'])) {
            foreach ($validated['organizations'] as $orgId) {
                $organization = Organization::find($orgId);
                if ($organization) {
                    $organization->addMember($user);
                }
            }
        }

        // Add to organization groups
        if (!empty($validated['organization_groups'])) {
            foreach ($validated['organization_groups'] as $groupId) {
                $group = OrganizationGroup::find($groupId);
                if ($group) {
                    $group->addMember($user);
                }
            }
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['roles', 'organizations', 'organizationGroups.organization', 'authMethods']);
        
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        $user->load(['roles', 'organizations', 'organizationGroups']);
        $roles = Role::all();
        $organizations = Organization::all();
        $organizationGroups = OrganizationGroup::with('organization')->get();

        return view('admin.users.edit', compact('user', 'roles', 'organizations', 'organizationGroups'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => ['nullable', 'string', 'max:20', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'primary_auth_method' => 'required|in:email_password,phone_password,email_otp,phone_otp',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'organizations' => 'array',
            'organizations.*' => 'exists:organizations,id',
            'organization_groups' => 'array',
            'organization_groups.*' => 'exists:organization_groups,id',
        ]);

        // Update user basic info
        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'primary_auth_method' => $validated['primary_auth_method'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Update roles
        if (auth()->user()->can('manage_user_permissions')) {
            if (!empty($validated['roles'])) {
                $roles = Role::whereIn('id', $validated['roles'])->get();
                $user->syncRoles($roles);
            } else {
                $user->syncRoles([]);
            }
        }

        // Update organizations
        $user->organizations()->detach();
        if (!empty($validated['organizations'])) {
            foreach ($validated['organizations'] as $orgId) {
                $organization = Organization::find($orgId);
                if ($organization) {
                    $organization->addMember($user);
                }
            }
        }

        // Update organization groups
        $user->organizationGroups()->detach();
        if (!empty($validated['organization_groups'])) {
            foreach ($validated['organization_groups'] as $groupId) {
                $group = OrganizationGroup::find($groupId);
                if ($group) {
                    $group->addMember($user);
                }
            }
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()
                           ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully.');
    }

    /**
     * Show form for managing user roles only
     */
    public function manageRoles(User $user)
    {
        $user->load(['roles']);
        $roles = Role::all();

        return view('admin.users.manage-roles-only', compact('user', 'roles'));
    }

    /**
     * Show form for managing user organizations and groups
     */
    public function manageOrganizations(User $user)
    {
        $user->load(['organizations', 'organizationGroups.organization']);
        $organizations = Organization::all();
        $organizationGroups = OrganizationGroup::with('organization')->get();

        return view('admin.users.manage-organizations', compact('user', 'organizations', 'organizationGroups'));
    }

    /**
     * Show the form for managing everything (combined)
     */
    public function manageAll(User $user)
    {
        $user->load(['roles', 'organizations', 'organizationGroups.organization']);
        $roles = Role::all();
        $organizations = Organization::all();
        $organizationGroups = OrganizationGroup::with('organization')->get();

        return view('admin.users.manage-roles', compact('user', 'roles', 'organizations', 'organizationGroups'));
    }

    /**
     * Update user roles only
     */
    public function updateRoles(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        // Update roles
        if (isset($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'User roles updated successfully.');
    }    /**
     * Update user organizations and groups
     */
    public function updateOrganizations(Request $request, User $user)
    {
        $validated = $request->validate([
            'organizations' => 'array',
            'organizations.*' => 'exists:organizations,id',
            'organization_groups' => 'array',
            'organization_groups.*' => 'exists:organization_groups,id',
        ]);

        // Update organizations first
        if (isset($validated['organizations'])) {
            $organizations = Organization::whereIn('id', $validated['organizations'])->get();
            $user->organizations()->sync($organizations);
        } else {
            $user->organizations()->sync([]);
        }

        // Refresh user to get updated organizations
        $user->refresh();
        $userOrgIds = $user->organizations->pluck('id')->toArray();

        // Update organization groups (only assign groups that belong to user's organizations)
        if (isset($validated['organization_groups']) && !empty($userOrgIds)) {
            $validGroups = OrganizationGroup::whereIn('id', $validated['organization_groups'])
                                           ->whereIn('organization_id', $userOrgIds)
                                           ->get();
            $user->organizationGroups()->sync($validGroups);
        } else {
            // If no organizations or groups selected, remove all group memberships
            $user->organizationGroups()->sync([]);
        }

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'User organizations and groups updated successfully.');
    }

    /**
     * Update user roles and organization memberships (combined)
     */
    public function updateAll(Request $request, User $user)
    {
        $validated = $request->validate([
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
            'organizations' => 'array',
            'organizations.*' => 'exists:organizations,id',
            'organization_groups' => 'array',
            'organization_groups.*' => 'exists:organization_groups,id',
        ]);

        // Update roles
        if (isset($validated['roles'])) {
            $roles = Role::whereIn('id', $validated['roles'])->get();
            $user->syncRoles($roles);
        } else {
            $user->syncRoles([]);
        }        // Update organizations
        if (isset($validated['organizations'])) {
            $organizations = Organization::whereIn('id', $validated['organizations'])->get();
            $user->organizations()->sync($organizations);
        } else {
            $user->organizations()->sync([]);
        }

        // Refresh user to get updated organizations
        $user->refresh();
        $userOrgIds = $user->organizations->pluck('id')->toArray();

        // Update organization groups (only assign groups that belong to user's organizations)
        if (isset($validated['organization_groups']) && !empty($userOrgIds)) {
            $validGroups = OrganizationGroup::whereIn('id', $validated['organization_groups'])
                                           ->whereIn('organization_id', $userOrgIds)
                                           ->get();
            $user->organizationGroups()->sync($validGroups);
        } else {
            $user->organizationGroups()->sync([]);
        }

        return redirect()->route('admin.users.show', $user)
                        ->with('success', 'User roles, organizations and groups updated successfully.');
    }
}
