<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublishedCourseEditTest extends TestCase
{
    use DatabaseTransactions;

    protected User $teacher;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'admin']);

        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');

        $this->category = Category::factory()->create();
    }

    private function createCourse(string $status = 'draft'): Course
    {
        return Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $this->category->id,
            'status' => $status,
        ]);
    }

    private function updatePayload(array $overrides = []): array
    {
        return array_merge([
            'title' => 'Updated Course Title',
            'category_id' => $this->category->id,
            'description' => 'Updated description',
            'price' => 500000,
            'level' => 'intermediate',
        ], $overrides);
    }

    public function test_editing_draft_course_does_not_change_status(): void
    {
        $course = $this->createCourse('draft');

        $this->actingAs($this->teacher)
            ->put(route('teacher.courses.update', $course), $this->updatePayload())
            ->assertRedirect();

        $course->refresh();
        $this->assertEquals('draft', $course->status);
        $this->assertEquals('Updated Course Title', $course->title);
    }

    public function test_editing_published_course_resets_to_pending_review(): void
    {
        $course = $this->createCourse('published');

        $response = $this->actingAs($this->teacher)
            ->put(route('teacher.courses.update', $course), $this->updatePayload());

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $course->refresh();
        $this->assertEquals('pending_review', $course->status);
        $this->assertEquals('Updated Course Title', $course->title);
    }

    public function test_editing_pending_review_course_stays_pending(): void
    {
        $course = $this->createCourse('pending_review');

        $this->actingAs($this->teacher)
            ->put(route('teacher.courses.update', $course), $this->updatePayload())
            ->assertRedirect();

        $course->refresh();
        $this->assertEquals('pending_review', $course->status);
    }

    public function test_admin_can_reapprove_after_edit(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $course = $this->createCourse('published');

        // Teacher edits published course
        $this->actingAs($this->teacher)
            ->put(route('teacher.courses.update', $course), $this->updatePayload());

        $course->refresh();
        $this->assertEquals('pending_review', $course->status);

        // Admin approves
        $this->actingAs($admin)
            ->post(route('admin.courses.approve', $course))
            ->assertRedirect();

        $course->refresh();
        $this->assertEquals('published', $course->status);
    }

    public function test_published_course_edit_page_shows_warning(): void
    {
        $course = $this->createCourse('published');

        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.courses.edit', $course));

        $response->assertOk();
        $response->assertSee(__('teacher.warning_edit_published'));
    }

    public function test_draft_course_edit_page_does_not_show_warning(): void
    {
        $course = $this->createCourse('draft');

        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.courses.edit', $course));

        $response->assertOk();
        $response->assertDontSee(__('teacher.warning_edit_published'));
    }
}
