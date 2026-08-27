<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NavigationLinksTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'admin']);
    }

    private function publishedCourse(): Course
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $category = Category::firstOrCreate(
            ['slug' => 'nav-links-cat'],
            ['name' => 'Danh Mục Kiểm Thử Liên Kết', 'sort_order' => 1],
        );

        return Course::create([
            'teacher_id' => $teacher->id,
            'category_id' => $category->id,
            'title' => 'Khóa Học Kiểm Thử Nút Vào Học',
            'slug' => 'khoa-hoc-kiem-thu-nut-vao-hoc',
            'price' => 499000,
            'level' => 'beginner',
            'status' => 'published',
        ]);
    }

    public function test_enrolled_student_gets_a_working_link_into_the_course_player(): void
    {
        $course = $this->publishedCourse();

        $student = User::factory()->create();
        $student->assignRole('student');

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('courses.show', $course->slug));

        $response->assertStatus(200);
        $response->assertSee('href="'.route('learn.show', $course->slug).'"', false);
    }

    public function test_course_player_link_wraps_the_go_to_learning_label(): void
    {
        $course = $this->publishedCourse();

        $student = User::factory()->create();
        $student->assignRole('student');

        Enrollment::create([
            'user_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
            'enrolled_at' => now(),
        ]);

        $response = $this->actingAs($student)->get(route('courses.show', $course->slug));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            route('learn.show', $course->slug),
            __('messages.go_to_learning'),
        ], false);
    }

    public function test_guest_sees_a_purchase_prompt_instead_of_the_player_link(): void
    {
        $course = $this->publishedCourse();

        $response = $this->get(route('courses.show', $course->slug));

        $response->assertStatus(200);
        $response->assertDontSee(route('learn.show', $course->slug), false);
    }

    /**
     * The footer already links to the teacher dashboard, so a bare assertSee on the
     * URL passes even when the header anchor is still a placeholder. Anchor the
     * assertion to the header's own label instead: the header uses
     * messages.teacher_dashboard while the footer uses teacher.portal, and the header
     * renders first. If the header href regresses, the URL only appears later in the
     * footer and the ordering no longer holds.
     */
    public function test_teacher_header_links_to_the_teacher_dashboard(): void
    {
        $teacher = User::factory()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            route('teacher.dashboard'),
            __('messages.teacher_dashboard'),
        ], false);
    }

    public function test_admin_header_links_to_the_admin_dashboard(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeInOrder([
            route('admin.dashboard'),
            __('messages.admin_dashboard'),
        ], false);
    }
}
