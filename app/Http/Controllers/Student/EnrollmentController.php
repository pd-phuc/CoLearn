<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;

class EnrollmentController extends Controller
{
    /**
     * Enroll the authenticated user in a free course directly,
     * bypassing the cart/checkout flow.
     */
    public function enrollFree(Course $course): RedirectResponse
    {
        $user = auth()->user();

        // Only published free courses can be enrolled directly
        if ($course->price > 0) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', __('messages.course_not_free'));
        }

        if ($course->status !== 'published') {
            abort(404);
        }

        // Already enrolled? Go to player
        if ($user->enrollments()->where('course_id', $course->id)->exists()) {
            return redirect()->route('learn.show', $course->slug)
                ->with('info', __('messages.already_enrolled_notice'));
        }

        Enrollment::create([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('learn.show', $course->slug)
            ->with('success', __('messages.enrolled_free_success'));
    }
}
