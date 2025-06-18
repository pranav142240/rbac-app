<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\OrganizationGroupController;
use App\Http\Controllers\Admin\UserManagementController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    // If user is already authenticated, redirect to dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Otherwise, redirect to login page
    return redirect()->route('auth.login');
})->name('home');

// Login route alias for authentication middleware
Route::get('/login', function () {
    return redirect()->route('auth.login');
})->name('login');

// Logout route alias
Route::post('/logout', function () {
    return redirect()->route('auth.logout');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    
    // Profile Management
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });
    
    /*
    |--------------------------------------------------------------------------
    | RBAC Management Routes
    |--------------------------------------------------------------------------
    */
    
    // Roles Management
    Route::middleware('permission:view_roles|create_roles|edit_roles|delete_roles')->group(function () {
        Route::resource('roles', RoleController::class);
    });
    
    // Permissions Management
    Route::middleware('permission:view_permissions|edit_permissions|delete_permissions')->group(function () {
        Route::resource('permissions', PermissionController::class)->except(['create', 'store']);
    });
    
    /*
    |--------------------------------------------------------------------------
    | Organization Management Routes
    |--------------------------------------------------------------------------
    */
      // Basic Organization Management (OrgController)
    Route::middleware(['permission:view_organizations|manage_organizations'])->group(function () {
        Route::get('organizations', [OrgController::class, 'index'])->name('organizations.index');
        Route::get('organizations/create', [OrgController::class, 'create'])->name('organizations.create');
        Route::post('organizations', [OrgController::class, 'store'])->name('organizations.store');
        
        // Routes that require organization access
        Route::middleware('organization.access')->group(function () {            Route::get('organizations/{organization}', [OrgController::class, 'show'])->name('organizations.show');
            Route::get('organizations/{organization}/edit', [OrgController::class, 'edit'])->name('organizations.edit');
            Route::put('organizations/{organization}', [OrgController::class, 'update'])->name('organizations.update');
            Route::delete('organizations/{organization}', [OrgController::class, 'destroy'])->name('organizations.destroy');
            Route::post('organizations/{organization}/toggle-status', [OrgController::class, 'toggleStatus'])->name('organizations.toggle-status');
            Route::get('organizations/{organization}/members', [OrgController::class, 'members'])->name('organizations.members');
            Route::post('organizations/{organization}/members', [OrgController::class, 'addMember'])->name('organizations.members.add');
            Route::delete('organizations/{organization}/members/{user}', [OrgController::class, 'removeMember'])->name('organizations.members.remove');});
    });

    // Organization Groups Management
    Route::middleware('permission:view_organization_groups|manage_organization_groups')->group(function () {
        // Routes that require organization access
        Route::middleware('organization.access')->group(function () {
            Route::resource('organizations.groups', OrganizationGroupController::class);
            Route::post('organizations/{organization}/groups/{organizationGroup}/users', [OrganizationGroupController::class, 'addUser'])->name('organizations.groups.users.add');
            Route::delete('organizations/{organization}/groups/{organizationGroup}/users/{user}', [OrganizationGroupController::class, 'removeUser'])->name('organizations.groups.users.remove');
        });
        
        // Standalone Organization Group routes
        Route::get('organization-groups', [OrganizationGroupController::class, 'indexStandalone'])->name('organization-groups.index');
        Route::get('organization-groups/{organizationGroup}', [OrganizationGroupController::class, 'showStandalone'])->name('organization-groups.show');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    
    Route::prefix('admin')->name('admin.')->middleware('permission:view_users|create_users|update_users|delete_users|manage_user_permissions')->group(function () {
        Route::resource('users', UserManagementController::class);
        
        // User Role Management
        Route::middleware('permission:manage_user_permissions')->group(function () {
            Route::get('users/{user}/manage-roles', [UserManagementController::class, 'manageRoles'])->name('users.manage-roles');
            Route::post('users/{user}/update-roles', [UserManagementController::class, 'updateRoles'])->name('users.update-roles');
        });
        
        // User Organization Management
        Route::middleware('permission:manage_organizations')->group(function () {
            Route::get('users/{user}/manage-organizations', [UserManagementController::class, 'manageOrganizations'])->name('users.manage-organizations');
            Route::post('users/{user}/update-organizations', [UserManagementController::class, 'updateOrganizations'])->name('users.update-organizations');
        });
        
        // Combined Management
        Route::middleware('permission:manage_user_permissions|manage_organizations')->group(function () {
            Route::get('users/{user}/manage-all', [UserManagementController::class, 'manageAll'])->name('users.manage-all');
            Route::post('users/{user}/update-all', [UserManagementController::class, 'updateAll'])->name('users.update-all');
        });
    });
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
