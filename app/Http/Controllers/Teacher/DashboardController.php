<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $teacherId = $request->user()->id;

        $coursesQuery = Course::where('teacher_id', $teacherId);

        $totalCourses = (clone $coursesQuery)->count();
        $publishedCourses = (clone $coursesQuery)->where('status', 'published')->count();
        $pendingCourses = (clone $coursesQuery)->where('status', 'pending_review')->count();
        $draftCourses = (clone $coursesQuery)->where('status', 'draft')->count();

        $courseIds = (clone $coursesQuery)->pluck('id');

        // Total distinct students enrolled in teacher's courses
        $totalStudents = Enrollment::whereIn('course_id', $courseIds)
            ->distinct('user_id')
            ->count('user_id');

        // Estimated revenue earned from teacher's courses (paid orders)
        $estimatedEarnings = OrderItem::whereIn('course_id', $courseIds)
            ->whereHas('order', function ($q) {
                $q->where('status', 'paid');
            })
            ->sum('price');

        // Recent enrollments in teacher's courses
        $recentEnrollments = Enrollment::with(['user', 'course'])
            ->whereIn('course_id', $courseIds)
            ->latest('enrolled_at')
            ->take(6)
            ->get();

        // Top courses by enrollment count
        $topCourses = Course::where('teacher_id', $teacherId)
            ->withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->take(5)
            ->get();

        return view('teacher.dashboard', compact(
            'totalCourses',
            'publishedCourses',
            'pendingCourses',
            'draftCourses',
            'totalStudents',
            'estimatedEarnings',
            'recentEnrollments',
            'topCourses',
        ));
    }
}
