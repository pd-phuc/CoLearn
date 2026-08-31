<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Teacher\Concerns\AuthorizesTeacherCourse;
use App\Http\Requests\Teacher\StoreLessonRequest;
use App\Http\Requests\Teacher\UpdateLessonRequest;
use App\Models\Lesson;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    use AuthorizesTeacherCourse;

    public function store(StoreLessonRequest $request, Section $section): RedirectResponse
    {
        $this->authorizeTeacher($request, $section->course);

        $validated = $request->validated();

        $slug = Str::slug($validated['title']);
        $originalSlug = $slug;
        $count = 1;
        while (Lesson::where('section_id', $section->id)->where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        $documentPath = null;
        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('courses/materials', 'public');
            $documentPath = Storage::url($path);
        }

        $maxSortOrder = $section->lessons()->max('sort_order') ?? 0;

        $section->lessons()->create([
            'title' => $validated['title'],
            'slug' => $slug,
            'type' => $validated['type'],
            'video_url' => $validated['video_url'] ?? null,
            'document_path' => $documentPath,
            'content' => $validated['content'] ?? null,
            'duration' => $validated['duration'] ?? 0,
            'is_free_preview' => $request->boolean('is_free_preview'),
            'sort_order' => $maxSortOrder + 1,
        ]);

        return back()->with('status', __('teacher.lesson_created'));
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $this->authorizeTeacher($request, $lesson->section->course);

        $validated = $request->validated();

        if ($request->hasFile('document_file')) {
            $path = $request->file('document_file')->store('courses/materials', 'public');
            $validated['document_path'] = Storage::url($path);
        }

        $validated['is_free_preview'] = $request->boolean('is_free_preview');

        $lesson->update($validated);

        return back()->with('status', __('teacher.lesson_updated'));
    }

    public function destroy(Request $request, Lesson $lesson): RedirectResponse
    {
        $this->authorizeTeacher($request, $lesson->section->course);

        $lesson->delete();

        return back()->with('status', __('teacher.lesson_deleted'));
    }
}
