<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    /**
     * Constructor to add middleware
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('permission:view_roles')->only(['index', 'show']);
        $this->middleware('permission:create_roles')->only(['create', 'store']);
        $this->middleware('permission:edit_roles')->only(['edit', 'update']);
        $this->middleware('permission:delete_roles')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::with('permissions')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $permissions = Permission::all();
        return view('roles.create', compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'guard_name' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($request) {
            $role = Role::create([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        $role->load('permissions', 'users');
        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        $permissions = Permission::all();
        $rolePermissions = $role->permissions->pluck('id')->toArray();
        return view('roles.edit', compact('role', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'guard_name' => 'nullable|string|max:255',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id'
        ]);

        DB::transaction(function () use ($request, $role) {
            $role->update([
                'name' => $request->name,
                'guard_name' => $request->guard_name ?? 'web'
            ]);

            if ($request->has('permissions')) {
                $role->syncPermissions($request->permissions);
            } else {
                $role->syncPermissions([]);
            }
        });

        return redirect()->route('roles.index')
            ->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        // Check if role has users assigned
        if ($role->users()->count() > 0) {
            return redirect()->route('roles.index')
                ->with('error', 'Cannot delete role because it has users assigned.');
        }

        $role->delete();

        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully.');
    }
}
