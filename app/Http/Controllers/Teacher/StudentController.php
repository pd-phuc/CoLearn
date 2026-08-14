<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
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

        return view('teacher.students.index', compact('enrollments', 'courses'));
    }
}
