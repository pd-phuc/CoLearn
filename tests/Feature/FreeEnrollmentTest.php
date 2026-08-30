<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FreeEnrollmentTest extends TestCase
{
    use DatabaseTransactions;

    protected User $student;

    protected Course $freeCourse;

    protected Course $paidCourse;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
        Role::firstOrCreate(['name' => 'student']);

        $this->student = User::factory()->create();
        $this->student->assignRole('student');

        $this->freeCourse = Course::factory()->create([
            'price' => 0,
            'status' => 'published',
        ]);

        $this->paidCourse = Course::factory()->create([
            'price' => 50000,
            'status' => 'published',
        ]);
    }

    public function test_free_course_enrolls_directly_and_redirects_to_player(): void
    {
        $response = $this->actingAs($this->student)
            ->post(route('courses.enroll-free', $this->freeCourse));

        $response->assertRedirect(route('learn.show', $this->freeCourse->slug));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->freeCourse->id,
            'status' => 'active',
        ]);
    }

    public function test_paid_course_cannot_use_enroll_free(): void
    {
        $response = $this->actingAs($this->student)
            ->post(route('courses.enroll-free', $this->paidCourse));

        $response->assertRedirect(route('courses.show', $this->paidCourse->slug));
        $response->assertSessionHas('error');

        $this->assertDatabaseMissing('enrollments', [
            'user_id' => $this->student->id,
            'course_id' => $this->paidCourse->id,
        ]);
    }

    public function test_already_enrolled_user_redirects_to_player(): void
    {
        Enrollment::create([
            'user_id' => $this->student->id,
            'course_id' => $this->freeCourse->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($this->student)
            ->post(route('courses.enroll-free', $this->freeCourse));

        $response->assertRedirect(route('learn.show', $this->freeCourse->slug));
        $response->assertSessionHas('info');

        // No duplicate enrollment
        $this->assertEquals(1, Enrollment::where([
            'user_id' => $this->student->id,
            'course_id' => $this->freeCourse->id,
        ])->count());
    }

    public function test_guest_cannot_enroll_free(): void
    {
        $response = $this->post(route('courses.enroll-free', $this->freeCourse));

        $response->assertRedirect(route('login'));
    }

    public function test_unpublished_free_course_returns_404(): void
    {
        $draftCourse = Course::factory()->create([
            'price' => 0,
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->student)
            ->post(route('courses.enroll-free', $draftCourse));

        $response->assertStatus(404);
    }
}
