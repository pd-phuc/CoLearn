<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\AuthorizesTeacherCourse;
use App\Http\Requests\Teacher\StoreSectionRequest;
use App\Http\Requests\Teacher\UpdateSectionRequest;
use App\Models\Course;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SectionController extends Controller
{
    use AuthorizesTeacherCourse;

    public function store(StoreSectionRequest $request, Course $course): RedirectResponse
    {
        $this->authorizeTeacher($request, $course);

        $validated = $request->validated();

        $maxSortOrder = $course->sections()->max('sort_order') ?? 0;

        $course->sections()->create([
            'title' => $validated['title'],
            'sort_order' => $maxSortOrder + 1,
        ]);

        return back()->with('status', __('teacher.section_created'));
    }

    public function update(UpdateSectionRequest $request, Section $section): RedirectResponse
    {
        $this->authorizeTeacher($request, $section->course);

        $section->update($request->validated());

        return back()->with('status', __('teacher.section_updated'));
    }

    public function destroy(Request $request, Section $section): RedirectResponse
    {
        $this->authorizeTeacher($request, $section->course);

        $section->delete();

        return back()->with('status', __('teacher.section_deleted'));
    }
}
