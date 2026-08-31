<?php

namespace App\Http\Controllers\Teacher\Concerns;

use App\Models\Course;
use Illuminate\Http\Request;

trait AuthorizesTeacherCourse
{
    /**
     * Ensure the authenticated user owns the course or has admin privileges.
     */
    protected function authorizeTeacher(Request $request, Course $course): void
    {
        if ($course->teacher_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            abort(403, __('messages.unauthorized_course_access'));
        }
    }
}
