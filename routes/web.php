<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\OrganizationGroupController;

Route::get('/', function () {
    // If user is already authenticated, redirect to dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Otherwise, redirect to login page
    return redirect()->route('auth.login');
});

// Login route alias for authentication middleware
Route::get('/login', function () {
    return redirect()->route('auth.login');
})->name('login');

// Logout route alias
Route::post('/logout', function () {
    return redirect()->route('auth.logout');
})->name('logout');

// Dashboard routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/analytics', [DashboardController::class, 'analytics'])->name('dashboard.analytics');
    Route::get('/dashboard/system-status', [DashboardController::class, 'systemStatus'])->name('dashboard.system-status');
});

Route::get('/rbac-test', function () {
    return view('rbac-test');
})->middleware(['auth'])->name('rbac.test');

// Profile Management Routes
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('roles', RoleController::class);
    Route::resource('permissions', PermissionController::class)->except(['create', 'store']);
      // Admin User Management routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', \App\Http\Controllers\Admin\UserManagementController::class);
        Route::get('users/{user}/manage-roles', [\App\Http\Controllers\Admin\UserManagementController::class, 'manageRoles'])->name('users.manage-roles');
        Route::post('users/{user}/update-roles', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateRoles'])->name('users.update-roles');
        Route::get('users/{user}/manage-organizations', [\App\Http\Controllers\Admin\UserManagementController::class, 'manageOrganizations'])->name('users.manage-organizations');
        Route::post('users/{user}/update-organizations', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateOrganizations'])->name('users.update-organizations');
        Route::get('users/{user}/manage-all', [\App\Http\Controllers\Admin\UserManagementController::class, 'manageAll'])->name('users.manage-all');
        Route::post('users/{user}/update-all', [\App\Http\Controllers\Admin\UserManagementController::class, 'updateAll'])->name('users.update-all');
    });
});

Route::middleware('auth')->group(function () {
    // Organization routes
    Route::resource('organizations', OrgController::class);
    Route::get('organizations/{organization}/members', [OrgController::class, 'members'])->name('organizations.members');
    Route::post('organizations/{organization}/members', [OrgController::class, 'addMember'])->name('organizations.members.add');
    Route::delete('organizations/{organization}/members/{user}', [OrgController::class, 'removeMember'])->name('organizations.members.remove');
    
    // Organization Groups routes
    Route::resource('organizations.groups', OrganizationGroupController::class, [
        'names' => [
            'index' => 'organizations.groups.index',
            'create' => 'organizations.groups.create',
            'store' => 'organizations.groups.store',
            'show' => 'organizations.groups.show',
            'edit' => 'organizations.groups.edit',
            'update' => 'organizations.groups.update',
            'destroy' => 'organizations.groups.destroy',
        ]
    ]);
      // Organization Group Members routes
    Route::post('organizations/{organization}/groups/{organizationGroup}/users', [OrganizationGroupController::class, 'addUser'])->name('organizations.groups.users.add');
    Route::delete('organizations/{organization}/groups/{organizationGroup}/users/{user}', [OrganizationGroupController::class, 'removeUser'])->name('organizations.groups.users.remove');
      // Standalone Organization Group routes
    Route::get('organization-groups', [OrganizationGroupController::class, 'indexStandalone'])->name('organization-groups.index');
    Route::get('organization-groups/{organizationGroup}', [OrganizationGroupController::class, 'showStandalone'])->name('organization-groups.show');
});


require __DIR__.'/auth.php';
