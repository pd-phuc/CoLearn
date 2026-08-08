<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class MyCoursesController extends Controller
{
    /**
     * Display enrolled courses for the logged-in user with progress stats.
     */
    public function index(): View
    {
        $user = Auth::user();

        $enrollments = Enrollment::with([
            'course.teacher',
            'course.category',
            'course.sections.lessons',
        ])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('enrolled_at')
            ->get();

        // Calculate progress percentage for each enrolled course
        $enrolledCourses = $enrollments->map(function ($enrollment) use ($user) {
            $course = $enrollment->course;
            $allLessons = $course->sections->flatMap->lessons;
            $totalLessonsCount = $allLessons->count();

            $completedLessonsCount = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $allLessons->pluck('id'))
                ->count();

            $progressPercent = $totalLessonsCount > 0
                ? round(($completedLessonsCount / $totalLessonsCount) * 100)
                : 0;

            $course->total_lessons_count = $totalLessonsCount;
            $course->completed_lessons_count = $completedLessonsCount;
            $course->progress_percent = $progressPercent;
            $course->enrolled_at = $enrollment->enrolled_at;

            return $course;
        });

        return view('profile.my-courses', compact('enrolledCourses'));
    }
}
