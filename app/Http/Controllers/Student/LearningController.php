<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\LessonCompletion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LearningController extends Controller
{
    public function show(Request $request, Course $course, ?Lesson $lesson = null): View|RedirectResponse
    {
        $user = Auth::user();

        // Check if course is enrolled
        $isEnrolled = false;
        if ($user) {
            $isEnrolled = Enrollment::where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('status', 'active')
                ->exists();
        }

        // Eager load sections and lessons
        $course->load([
            'teacher',
            'category',
            'sections' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            },
            'sections.lessons' => function ($query) {
                $query->orderBy('sort_order', 'asc');
            },
        ]);

        $allLessons = $course->sections->flatMap->lessons;

        if ($allLessons->isEmpty()) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', __('messages.no_lessons_in_course'));
        }

        // If no lesson specified, default to first lesson or first uncompleted lesson
        if (! $lesson) {
            if ($user && $isEnrolled) {
                $completedLessonIds = LessonCompletion::where('user_id', $user->id)
                    ->whereIn('lesson_id', $allLessons->pluck('id'))
                    ->pluck('lesson_id')
                    ->toArray();

                $uncompletedLesson = $allLessons->first(function ($item) use ($completedLessonIds) {
                    return ! in_array($item->id, $completedLessonIds);
                });

                $lesson = $uncompletedLesson ?: $allLessons->first();
            } else {
                $lesson = $allLessons->first();
            }
        }

        // Check permission for target lesson
        if (! $isEnrolled && ! $lesson->is_free_preview) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', __('messages.enrollment_required_to_access'));
        }

        // Completed lessons list for current user
        $completedLessonIds = [];
        if ($user) {
            $completedLessonIds = LessonCompletion::where('user_id', $user->id)
                ->whereIn('lesson_id', $allLessons->pluck('id'))
                ->pluck('lesson_id')
                ->toArray();
        }

        $totalLessonsCount = $allLessons->count();
        $completedCount = count($completedLessonIds);
        $progressPercent = $totalLessonsCount > 0 ? round(($completedCount / $totalLessonsCount) * 100) : 0;

        // Next & Previous lessons
        $currentLessonIndex = $allLessons->search(function ($item) use ($lesson) {
            return $item->id === $lesson->id;
        });

        $prevLesson = $currentLessonIndex > 0 ? $allLessons->get($currentLessonIndex - 1) : null;
        $nextLesson = $currentLessonIndex !== false && $currentLessonIndex < $totalLessonsCount - 1
            ? $allLessons->get($currentLessonIndex + 1)
            : null;

        $isCurrentCompleted = in_array($lesson->id, $completedLessonIds);

        return view('student.learn', compact(
            'course',
            'lesson',
            'allLessons',
            'isEnrolled',
            'completedLessonIds',
            'progressPercent',
            'completedCount',
            'totalLessonsCount',
            'prevLesson',
            'nextLesson',
            'isCurrentCompleted',
        ));
    }

    public function toggleComplete(Request $request, Lesson $lesson): JsonResponse|RedirectResponse
    {
        $user = Auth::user();

        if (! $user) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }

            return redirect()->route('login');
        }

        $existing = LessonCompletion::where('user_id', $user->id)
            ->where('lesson_id', $lesson->id)
            ->first();

        if ($existing) {
            $existing->delete();
            $completed = false;
        } else {
            LessonCompletion::create([
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'completed_at' => now(),
            ]);
            $completed = true;
        }

        // Recalculate progress for this course
        $section = $lesson->section;
        $course = $section->course;
        $allLessonIds = $course->sections()->with('lessons')->get()->flatMap->lessons->pluck('id');

        $totalCount = $allLessonIds->count();
        $completedCount = LessonCompletion::where('user_id', $user->id)
            ->whereIn('lesson_id', $allLessonIds)
            ->count();

        $progressPercent = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;

        if ($request->wantsJson()) {
            return response()->json([
                'completed' => $completed,
                'completed_count' => $completedCount,
                'total_count' => $totalCount,
                'progress_percent' => $progressPercent,
            ]);
        }

        return back();
    }
}
