<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{    /**
     * Display the main dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Check if user is an admin
        if ($user->hasRole('Admin')) {
            return $this->adminDashboard();
        }
        
        // Regular user dashboard
        return $this->userDashboard();
    }    /**
     * Display the admin dashboard.
     */
    private function adminDashboard(): View
    {
        $user = auth()->user();
        
        // Admin stats
        $stats = [
            'total_users' => User::count(),
            'new_users_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
            'active_users_this_week' => User::where('updated_at', '>=', Carbon::now()->subWeek())->count(),
            'total_organizations' => \App\Models\Organization::count(),
        ];        // Recent activity for admin
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // System activity
        $systemActivity = [
            'last_login' => $user->updated_at,
            'total_roles' => \Spatie\Permission\Models\Role::count(),
            'total_permissions' => \Spatie\Permission\Models\Permission::count(),
        ];

        return view('dashboards.admin', compact(
            'stats', 
            'recentUsers', 
            'systemActivity'
        ));
    }

    /**
     * Display the user dashboard.
     */
    private function userDashboard(): View
    {
        $user = auth()->user();
        
        // User-specific stats - safe handling of relationships
        $stats = [
            'my_organizations' => $user->organizations()->count(),
            'my_groups' => $user->organizationGroups()->count(),
            'auth_methods' => $user->authMethods()->count(),
        ];

        // User's personal activity
        $userActivity = [
            'last_login' => $user->updated_at,
            'member_since' => $user->created_at,
        ];        return view('dashboards.user', compact(
            'stats', 
            'userActivity'
        ));
    }
}
