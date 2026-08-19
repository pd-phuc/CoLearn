<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LessonCompletion;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentController extends Controller
{
    public function index(Request $request): View
    {
        $teacherId = $request->user()->id;

        $courses = Course::where('teacher_id', $teacherId)
            ->with(['sections.lessons'])
            ->get();

        $courseIds = $courses->pluck('id');

        $query = Enrollment::with(['user', 'course.sections.lessons'])
            ->whereIn('course_id', $courseIds);

        if ($request->filled('course_id')) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'ilike', '%'.$request->search.'%')
                    ->orWhere('email', 'ilike', '%'.$request->search.'%');
            });
        }

        $enrollments = $query->latest('enrolled_at')->paginate(15)->withQueryString();

        // Pre-compute lesson completion counts to avoid N+1 in the view
        $enrollmentData = $enrollments->getCollection();

        // Build a lookup of lesson IDs per course
        $lessonIdsByCourse = [];
        foreach ($enrollmentData as $enr) {
            if (! isset($lessonIdsByCourse[$enr->course_id])) {
                $lessonIdsByCourse[$enr->course_id] = $enr->course->sections->flatMap->lessons->pluck('id')->all();
            }
        }

        // Batch query all relevant completions
        $allLessonIds = collect($lessonIdsByCourse)->flatten()->unique()->values()->all();
        $allUserIds = $enrollmentData->pluck('user_id')->unique()->values()->all();

        $completionCounts = collect();
        if (! empty($allLessonIds) && ! empty($allUserIds)) {
            $completionCounts = LessonCompletion::whereIn('user_id', $allUserIds)
                ->whereIn('lesson_id', $allLessonIds)
                ->get()
                ->groupBy('user_id');
        }

        // Attach computed progress to each enrollment
        $progressMap = [];
        foreach ($enrollmentData as $enr) {
            $totalLessons = count($lessonIdsByCourse[$enr->course_id] ?? []);
            $lessonIdsForCourse = $lessonIdsByCourse[$enr->course_id] ?? [];
            $userCompletions = $completionCounts[$enr->user_id] ?? collect();
            $completedLessons = $userCompletions->whereIn('lesson_id', $lessonIdsForCourse)->count();
            $progressPct = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

            $progressMap[$enr->user_id.'-'.$enr->course_id] = [
                'total' => $totalLessons,
                'completed' => $completedLessons,
                'percent' => $progressPct,
            ];
        }

        return view('teacher.students.index', compact('enrollments', 'courses', 'progressMap'));
    }
}
