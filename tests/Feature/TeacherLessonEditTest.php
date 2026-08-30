<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TeacherLessonEditTest extends TestCase
{
    use DatabaseTransactions;

    protected User $teacher;

    protected User $otherTeacher;

    protected Course $course;

    protected Section $section;

    protected Lesson $lesson;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
        Role::firstOrCreate(['name' => 'teacher']);

        $this->teacher = User::factory()->create();
        $this->teacher->assignRole('teacher');

        $this->otherTeacher = User::factory()->create();
        $this->otherTeacher->assignRole('teacher');

        $category = Category::firstOrCreate(
            ['slug' => 'test-cat'],
            ['name' => 'Test', 'sort_order' => 1],
        );

        $this->course = Course::factory()->create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $category->id,
            'status' => 'draft',
        ]);

        $this->section = Section::create([
            'course_id' => $this->course->id,
            'title' => 'Section 1',
            'sort_order' => 1,
        ]);

        $this->lesson = Lesson::create([
            'section_id' => $this->section->id,
            'title' => 'Original Title',
            'slug' => 'original-title',
            'type' => 'video',
            'video_url' => 'https://youtube.com/watch?v=old',
            'duration' => 10,
            'is_free_preview' => false,
            'sort_order' => 1,
        ]);
    }

    public function test_teacher_can_update_lesson_title(): void
    {
        $response = $this->actingAs($this->teacher)->put(
            route('teacher.lessons.update', $this->lesson),
            [
                'title' => 'Updated Title',
                'type' => 'video',
                'video_url' => 'https://youtube.com/watch?v=new',
                'duration' => 20,
                'is_free_preview' => 1,
                'sort_order' => 2,
            ],
        );

        $response->assertRedirect();
        $response->assertSessionHas('status');

        $this->lesson->refresh();
        $this->assertEquals('Updated Title', $this->lesson->title);
        $this->assertEquals('https://youtube.com/watch?v=new', $this->lesson->video_url);
        $this->assertEquals(20, $this->lesson->duration);
        $this->assertTrue($this->lesson->is_free_preview);
        $this->assertEquals(2, $this->lesson->sort_order);
    }

    public function test_teacher_can_change_lesson_type_to_text(): void
    {
        $response = $this->actingAs($this->teacher)->put(
            route('teacher.lessons.update', $this->lesson),
            [
                'title' => 'Text Lesson',
                'type' => 'text',
                'content' => 'This is the lesson content.',
                'duration' => 5,
            ],
        );

        $response->assertRedirect();

        $this->lesson->refresh();
        $this->assertEquals('text', $this->lesson->type);
        $this->assertEquals('This is the lesson content.', $this->lesson->content);
    }

    public function test_other_teacher_cannot_edit_lesson(): void
    {
        $response = $this->actingAs($this->otherTeacher)->put(
            route('teacher.lessons.update', $this->lesson),
            [
                'title' => 'Hijacked',
                'type' => 'video',
            ],
        );

        $response->assertForbidden();

        $this->lesson->refresh();
        $this->assertEquals('Original Title', $this->lesson->title);
    }

    public function test_edit_form_is_visible_on_course_edit_page(): void
    {
        $response = $this->actingAs($this->teacher)
            ->get(route('teacher.courses.edit', $this->course));

        $response->assertStatus(200);
        $response->assertSee('editLessonOpen');
        $response->assertSee(route('teacher.lessons.update', $this->lesson));
    }
}
