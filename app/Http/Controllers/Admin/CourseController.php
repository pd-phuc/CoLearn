<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CourseController extends Controller
{
    public function index(Request $request): View
    {
        $query = Course::with('teacher', 'category');

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($search = $request->input('search')) {
            $query->where('title', 'ilike', "%{$search}%");
        }

        $courses = $query->latest()->paginate(20)->withQueryString();

        return view('admin.courses.index', compact('courses'));
    }

    public function show(Course $course): View
    {
        $course->load(['teacher', 'category', 'sections.lessons', 'enrollments']);

        return view('admin.courses.show', compact('course'));
    }

    public function approve(Course $course): RedirectResponse
    {
        $course->update([
            'status' => 'published',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'rejection_reason' => null,
        ]);

        return back()->with('success', __('admin.course_approved'));
    }

    public function reject(Request $request, Course $course): RedirectResponse
    {
        $request->validate(['rejection_reason' => ['required', 'string', 'max:1000']]);

        $course->update([
            'status' => 'draft',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'rejection_reason' => $request->input('rejection_reason'),
        ]);

        return back()->with('success', __('admin.course_rejected'));
    }
}
