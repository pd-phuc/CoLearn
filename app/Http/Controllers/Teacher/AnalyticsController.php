<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    public function index(Request $request): View
    {
        $teacherId = $request->user()->id;
        $courseIds = Course::where('teacher_id', $teacherId)->pluck('id');

        // Monthly revenue for the last 12 months
        $months = [];
        $monthlyRevenue = [];
        $monthlyEnrollments = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $months[] = $date->format('M Y');

            $rev = OrderItem::whereIn('course_id', $courseIds)
                ->whereHas('order', function ($q) use ($date) {
                    $q->where('status', 'paid')
                        ->whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month);
                })
                ->sum('price');

            $monthlyRevenue[] = (int) $rev;

            $enr = Enrollment::whereIn('course_id', $courseIds)
                ->whereYear('enrolled_at', $date->year)
                ->whereMonth('enrolled_at', $date->month)
                ->count();

            $monthlyEnrollments[] = $enr;
        }

        // Detailed course performance list
        $courseStats = Course::where('teacher_id', $teacherId)
            ->withCount(['enrollments'])
            ->get()
            ->map(function ($course) {
                $revenue = OrderItem::where('course_id', $course->id)
                    ->whereHas('order', function ($q) {
                        $q->where('status', 'paid');
                    })
                    ->sum('price');

                $course->total_revenue = $revenue;

                return $course;
            });

        return view('teacher.analytics.index', compact(
            'months',
            'monthlyRevenue',
            'monthlyEnrollments',
            'courseStats',
        ));
    }
}
