<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();
        $startOfLastMonth = $now->copy()->subMonth()->startOfMonth();
        $endOfLastMonth = $now->copy()->subMonth()->endOfMonth();

        // Current month stats
        $revenueThisMonth = Order::where('status', 'paid')
            ->whereBetween('paid_at', [$startOfMonth, $now])
            ->sum('total_amount');

        $revenueLastMonth = Order::where('status', 'paid')
            ->whereBetween('paid_at', [$startOfLastMonth, $endOfLastMonth])
            ->sum('total_amount');

        $ordersThisMonth = Order::whereBetween('created_at', [$startOfMonth, $now])->count();
        $ordersLastMonth = Order::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $newUsersThisMonth = User::whereBetween('created_at', [$startOfMonth, $now])->count();
        $newUsersLastMonth = User::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $enrollmentsThisMonth = Enrollment::whereBetween('created_at', [$startOfMonth, $now])->count();
        $enrollmentsLastMonth = Enrollment::whereBetween('created_at', [$startOfLastMonth, $endOfLastMonth])->count();

        $stats = [
            'revenue' => [
                'value' => $revenueThisMonth,
                'previous' => $revenueLastMonth,
                'change' => $this->percentChange($revenueThisMonth, $revenueLastMonth),
            ],
            'orders' => [
                'value' => $ordersThisMonth,
                'previous' => $ordersLastMonth,
                'change' => $this->percentChange($ordersThisMonth, $ordersLastMonth),
            ],
            'new_users' => [
                'value' => $newUsersThisMonth,
                'previous' => $newUsersLastMonth,
                'change' => $this->percentChange($newUsersThisMonth, $newUsersLastMonth),
            ],
            'enrollments' => [
                'value' => $enrollmentsThisMonth,
                'previous' => $enrollmentsLastMonth,
                'change' => $this->percentChange($enrollmentsThisMonth, $enrollmentsLastMonth),
            ],
            'pending_courses' => Course::where('status', 'pending_review')->count(),
        ];

        // Revenue chart — last 12 months
        $revenueChart = $this->getMonthlyRevenue(12);

        // Enrollments chart — last 30 days
        $enrollmentChart = $this->getDailyEnrollments(30);

        // Top courses by enrollment
        $topCourses = Course::withCount('enrollments')
            ->has('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit(5)
            ->get();

        $maxEnrollments = $topCourses->max('enrollments_count') ?: 1;

        // Recent data
        $recentOrders = Order::with('user')
            ->latest()
            ->limit(5)
            ->get();

        $pendingCourses = Course::where('status', 'pending_review')
            ->with('teacher')
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'revenueChart',
            'enrollmentChart',
            'topCourses',
            'maxEnrollments',
            'recentOrders',
            'pendingCourses',
        ));
    }

    private function percentChange(float $current, float $previous): ?float
    {
        if ($previous == 0) {
            return $current > 0 ? 100.0 : null;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function getMonthlyRevenue(int $months): array
    {
        $start = now()->subMonths($months - 1)->startOfMonth();

        $data = Order::where('status', 'paid')
            ->where('paid_at', '>=', $start)
            ->selectRaw("TO_CHAR(paid_at, 'YYYY-MM') as month, SUM(total_amount) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $values = [];
        $period = CarbonPeriod::create($start, '1 month', now());

        foreach ($period as $date) {
            $key = $date->format('Y-m');
            $labels[] = $date->format('M Y');
            $values[] = (float) ($data[$key] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }

    private function getDailyEnrollments(int $days): array
    {
        $start = now()->subDays($days - 1)->startOfDay();

        $data = Enrollment::where('created_at', '>=', $start)
            ->selectRaw("DATE(created_at) as day, COUNT(*) as total")
            ->groupBy('day')
            ->orderBy('day')
            ->pluck('total', 'day')
            ->toArray();

        $labels = [];
        $values = [];
        $period = CarbonPeriod::create($start, '1 day', now());

        foreach ($period as $date) {
            $key = $date->format('Y-m-d');
            $labels[] = $date->format('d/m');
            $values[] = (int) ($data[$key] ?? 0);
        }

        return ['labels' => $labels, 'values' => $values];
    }
}
