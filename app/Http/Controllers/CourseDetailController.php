<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CourseDetailController extends Controller
{
    public function show(string $slug): View
    {
        $course = Course::with([
            'teacher',
            'category',
            'sections' => function ($q) {
                $q->orderBy('sort_order');
            },
            'sections.lessons' => function ($q) {
                $q->orderBy('sort_order');
            },
        ])->where('slug', $slug)->published()->firstOrFail();

        // Check enrollment status
        $isEnrolled = false;
        if (Auth::check()) {
            $user = Auth::user();
            $isEnrolled = $user->isAdmin() ||
                          ($user->isTeacher() && $course->teacher_id === $user->id) ||
                          $course->enrollments()->where('user_id', $user->id)->where('status', 'active')->exists();
        }

        // Stats calculation
        $allLessons = $course->sections->flatMap->lessons;
        $totalLessonsCount = $allLessons->count();
        $totalDurationSeconds = $allLessons->sum('duration');
        $freePreviewCount = $allLessons->where('is_free_preview', true)->count();

        // Format duration into hours and minutes using i18n
        $hours = floor($totalDurationSeconds / 3600);
        $minutes = floor(($totalDurationSeconds % 3600) / 60);
        if ($hours > 0) {
            $formattedDuration = __('messages.duration_format', ['hours' => $hours, 'minutes' => $minutes]);
        } else {
            $formattedDuration = __('messages.duration_minutes', ['minutes' => $minutes]);
        }

        // Discount percent calculation
        $discountPercent = 0;
        if ($course->discount_price && $course->price > 0) {
            $discountPercent = round((($course->price - $course->discount_price) / $course->price) * 100);
        }

        return view('courses.show', compact(
            'course',
            'isEnrolled',
            'totalLessonsCount',
            'totalDurationSeconds',
            'formattedDuration',
            'freePreviewCount',
            'discountPercent',
        ));
    }
}
