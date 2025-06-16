<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the main dashboard.
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Basic stats that all users can see
        $stats = [
            'total_users' => User::count(),
            'new_users_this_month' => User::whereMonth('created_at', Carbon::now()->month)->count(),
        ];        // Recent activity
        $recentUsers = User::latest()
            ->limit(5)
            ->get();

        // User's personal activity
        $userActivity = [
            'last_login' => $user->updated_at,
        ];

        return view('dashboard', compact(
            'stats', 
            'recentUsers', 
            'userActivity'
        ));
    }    /**
     * Get dashboard analytics data for AJAX requests.
     */
    public function analytics(Request $request): array
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        $analytics = [
            'user_growth' => User::where('created_at', '>=', $startDate)
                ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
        ];

        return response()->json($analytics);
    }    /**
     * Display system health/status.
     */
    public function systemStatus(): View
    {
        $systemStats = [
            'database_size' => $this->getDatabaseSize(),
            'total_records' => [
                'users' => User::count(),
            ],
            'system_health' => [
                'cache_status' => $this->checkCacheStatus(),
                'database_status' => $this->checkDatabaseStatus(),
                'storage_status' => $this->checkStorageStatus(),
            ]
        ];

        return view('dashboard.system-status', compact('systemStats'));
    }

    /**
     * Get database size (simplified).
     */
    private function getDatabaseSize(): string
    {
        try {
            $size = DB::select("
                SELECT 
                    ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) AS 'size_mb'
                FROM information_schema.tables 
                WHERE table_schema = DATABASE()
            ");
            
            return ($size[0]->size_mb ?? 0) . ' MB';
        } catch (\Exception $e) {
            return 'Unknown';
        }
    }

    /**
     * Check cache status.
     */
    private function checkCacheStatus(): bool
    {
        try {
            cache()->put('health_check', 'ok', 60);
            return cache()->get('health_check') === 'ok';
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check database status.
     */
    private function checkDatabaseStatus(): bool
    {
        try {
            DB::connection()->getPdo();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Check storage status.
     */
    private function checkStorageStatus(): bool
    {
        try {
            $storageAvailable = disk_free_space(storage_path());
            return $storageAvailable > (100 * 1024 * 1024); // 100MB minimum
        } catch (\Exception $e) {
            return false;
        }
    }
}
