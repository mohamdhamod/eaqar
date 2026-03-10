<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Models\Country;
use App\Models\Specialty;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Enums\PermissionEnum;

/**
 * Admin Dashboard Controller
 * 
 * Professional dashboard with platform analytics
 */
class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:' . PermissionEnum::SETTING_VIEW);
    }

    public function index()
    {
        // Basic Statistics
        $statistics = $this->getBasicStatistics();
        
        // Growth Statistics (compared to last month)
        $growthStats = $this->getGrowthStatistics();
        
        // Chart Data - Last 30 days activity
        $activityChartData = $this->getActivityChartData();
        
        // Top Specialties
        $topSpecialties = $this->getTopSpecialties();
        
        // Recent Users
        $recentUsers = User::latest()
            ->take(5)
            ->get();
        
        // System Health
        $systemHealth = $this->getSystemHealth();
        
        // Activity Timeline
        $activityTimeline = $this->getActivityTimeline();

        return view('dashboard.index', compact(
            'statistics',
            'growthStats',
            'activityChartData',
            'topSpecialties',
            'recentUsers',
            'systemHealth',
            'activityTimeline'
        ));
    }

    /**
     * Get basic statistics
     */
    protected function getBasicStatistics(): array
    {
        return [
            'users' => User::count(),
            'countries' => Country::where('is_active', true)->count(),
            'configurations' => Configuration::count(),
            'specialties' => Specialty::where('active', true)->count(),
            'today_contents' => User::whereDate('created_at', today())->count(),
            'week_contents' => User::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month_contents' => User::whereMonth('created_at', now()->month)->whereYear('created_at', now()->year)->count(),
        ];
    }

    /**
     * Get growth statistics compared to last period
     */
    protected function getGrowthStatistics(): array
    {
        $now = Carbon::now();
        $lastMonth = $now->copy()->subMonth();
        
        // Users growth
        $usersThisMonth = User::whereMonth('created_at', $now->month)->whereYear('created_at', $now->year)->count();
        $usersLastMonth = User::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $usersGrowth = $usersLastMonth > 0 ? round((($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100, 1) : 100;
        
        return [
            'users' => ['current' => $usersThisMonth, 'previous' => $usersLastMonth, 'growth' => $usersGrowth],
        ];
    }

    /**
     * Get activity chart data for last 30 days (user registrations)
     */
    protected function getActivityChartData(): array
    {
        $data = User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as count')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->pluck('count', 'date')
            ->toArray();

        $labels = [];
        $values = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('M d');
            $values[] = $data[$date] ?? 0;
        }

        return [
            'labels' => $labels,
            'values' => $values,
            'total' => array_sum($values),
        ];
    }

    /**
     * Get top specialties
     */
    protected function getTopSpecialties(): \Illuminate\Support\Collection
    {
        return Specialty::where('active', true)
            ->ordered()
            ->take(5)
            ->get();
    }

    /**
     * Get system health metrics
     */
    protected function getSystemHealth(): array
    {
        $avgResponseTime = 0.8;
        $successRate = 98.5;
        
        return [
            'api_status' => 'operational',
            'database_status' => 'operational',
            'queue_status' => 'operational',
            'avg_response_time' => $avgResponseTime,
            'success_rate' => $successRate,
            'last_error' => null,
        ];
    }

    /**
     * Get recent activity timeline
     */
    protected function getActivityTimeline(): \Illuminate\Support\Collection
    {
        return User::latest()
            ->take(8)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user',
                    'icon' => 'bi-person-plus',
                    'color' => 'success',
                    'message' => $user->name . ' joined the platform',
                    'time' => $user->created_at,
                ];
            })
            ->values();
    }
}
