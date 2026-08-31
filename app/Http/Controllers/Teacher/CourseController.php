<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\AuthorizesTeacherCourse;
use App\Http\Requests\Teacher\StoreCourseRequest;
use App\Http\Requests\Teacher\UpdateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CourseController extends Controller
{
    use AuthorizesTeacherCourse;

    public function index(Request $request): View
    {
        $teacherId = $request->user()->id;

        $query = Course::where('teacher_id', $teacherId)
            ->with(['category'])
            ->withCount(['sections', 'enrollments']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('title', 'ilike', '%'.$request->search.'%');
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('teacher.courses.index', compact('courses'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('teacher.courses.create', compact('categories'));
    }

    public function store(StoreCourseRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Course::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $thumbnailPath = Storage::url($thumbnailPath);
        }

        $learningOutcomes = array_values(array_filter(array_map('trim', explode("\n", $validated['learning_outcomes'] ?? ''))));
        $requirements = array_values(array_filter(array_map('trim', explode("\n", $validated['requirements'] ?? ''))));

        $course = Course::create([
            'teacher_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'price' => (int) $validated['price'],
            'discount_price' => ! empty($validated['discount_price']) ? (int) $validated['discount_price'] : null,
            'level' => $validated['level'],
            'learning_outcomes' => $learningOutcomes,
            'requirements' => $requirements,
            'thumbnail' => $thumbnailPath,
            'status' => 'draft',
        ]);

        return redirect()->route('teacher.courses.edit', $course)
            ->with('status', __('teacher.course_created_success'));
    }

    public function edit(Request $request, Course $course): View
    {
        $this->authorizeTeacher($request, $course);

        $categories = Category::orderBy('name')->get();
        $course->load(['category', 'sections.lessons']);

        return view('teacher.courses.edit', compact('course', 'categories'));
    }

    public function update(UpdateCourseRequest $request, Course $course): RedirectResponse
    {
        $this->authorizeTeacher($request, $course);

        $validated = $request->validated();

        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail from storage
            if ($course->thumbnail) {
                $oldPath = str_replace('/storage/', '', $course->thumbnail);
                Storage::disk('public')->delete($oldPath);
            }

            $thumbnailPath = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $validated['thumbnail'] = Storage::url($thumbnailPath);
        }

        $validated['learning_outcomes'] = array_values(array_filter(array_map('trim', explode("\n", $validated['learning_outcomes'] ?? ''))));
        $validated['requirements'] = array_values(array_filter(array_map('trim', explode("\n", $validated['requirements'] ?? ''))));
        $validated['price'] = (int) $validated['price'];
        $validated['discount_price'] = ! empty($validated['discount_price']) ? (int) $validated['discount_price'] : null;

        $wasPublished = $course->status === 'published';

        $course->update($validated);

        if ($wasPublished) {
            $course->update([
                'status' => 'pending_review',
                'rejection_reason' => null,
            ]);

            return back()->with('status', __('teacher.course_sent_for_review'));
        }

        return back()->with('status', __('teacher.course_updated_success'));
    }

    public function submitReview(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeTeacher($request, $course);

        $course->load('sections.lessons');

        if ($course->sections->isEmpty() || $course->sections->pluck('lessons')->flatten()->isEmpty()) {
            return back()->with('error', __('teacher.error_empty_curriculum'));
        }

        $course->update([
            'status' => 'pending_review',
            'rejection_reason' => null,
        ]);

        return back()->with('status', __('teacher.course_submitted_success'));
    }

    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $this->authorizeTeacher($request, $course);

        if ($course->status !== 'draft') {
            return back()->with('error', __('teacher.error_only_draft_deletable'));
        }

        $course->delete();

        return redirect()->route('teacher.courses.index')
            ->with('status', __('teacher.course_deleted_success'));
    }
}
