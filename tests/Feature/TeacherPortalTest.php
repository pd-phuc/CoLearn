<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Course;
use App\Models\Section;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TeacherPortalTest extends TestCase
{
    use DatabaseTransactions;

    protected User $teacher;

    protected Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);

        Role::firstOrCreate(['name' => 'teacher']);
        Role::firstOrCreate(['name' => 'student']);
        Role::firstOrCreate(['name' => 'admin']);

        $this->teacher = User::factory()->create([
            'name' => 'Thầy Nguyễn Văn An',
            'email' => 'teacher.test@colearn.test',
            'headline' => 'Kỹ sư phần mềm cao cấp & Giảng viên Lập trình',
            'bio' => 'Hơn 10 năm kinh nghiệm phát triển hệ thống và đào tạo hàng ngàn lập trình viên.',
        ]);
        $this->teacher->assignRole('teacher');

        $this->category = Category::firstOrCreate(
            ['slug' => 'lap-trinh-web'],
            ['name' => 'Lập Trình Web', 'sort_order' => 1],
        );
    }

    public function test_teacher_can_access_dashboard_and_pages(): void
    {
        $response = $this->actingAs($this->teacher)->get(route('teacher.dashboard'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->teacher)->get(route('teacher.courses.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->teacher)->get(route('teacher.courses.create'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->teacher)->get(route('teacher.students.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->teacher)->get(route('teacher.analytics.index'));
        $response->assertStatus(200);

        $response = $this->actingAs($this->teacher)->get(route('teacher.profile.edit'));
        $response->assertStatus(200);
    }

    public function test_teacher_can_create_course_with_full_accented_vietnamese(): void
    {
        $payload = [
            'title' => 'Khóa Học Lập Trình Laravel 13 & Vue 3 Thực Chiến',
            'category_id' => $this->category->id,
            'price' => 799000,
            'discount_price' => 599000,
            'level' => 'intermediate',
            'description' => 'Khóa học toàn diện về kiến trúc backend Laravel, bảo mật Sanctum, thanh toán SePay và triển khai Docker.',
            'learning_outcomes' => "Nắm vững kiến thức chuyên sâu về Eloquent\nTự tay thiết kế REST API chuẩn mực\nTích hợp cổng thanh toán VietQR SePay tự động",
            'requirements' => "Đã biết cơ bản về ngôn ngữ PHP\nĐã có kiến thức nền tảng về HTML, CSS, JavaScript",
        ];

        $response = $this->actingAs($this->teacher)->post(route('teacher.courses.store'), $payload);

        $course = Course::where('teacher_id', $this->teacher->id)->first();
        $this->assertNotNull($course);
        $this->assertEquals('Khóa Học Lập Trình Laravel 13 & Vue 3 Thực Chiến', $course->title);
        $this->assertEquals('khoa-hoc-lap-trinh-laravel-13-vue-3-thuc-chien', $course->slug);
        $this->assertEquals('draft', $course->status);

        $response->assertRedirect(route('teacher.courses.edit', $course));
    }

    public function test_teacher_can_add_section_and_lesson_with_accented_vietnamese(): void
    {
        $course = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $this->category->id,
            'title' => 'Khóa Học Thiết Kế Cơ Sở Dữ Liệu PostgreSQL',
            'slug' => 'khoa-hoc-thiet-ke-co-so-du-lieu-postgresql',
            'price' => 499000,
            'level' => 'beginner',
            'status' => 'draft',
        ]);

        // Add section with Vietnamese accents
        $sectionResponse = $this->actingAs($this->teacher)->post(
            route('teacher.courses.sections.store', $course),
            ['title' => 'Chương 1: Khởi Tạo Dự Án & Thiết Kế Bảng Dữ Liệu'],
        );
        $sectionResponse->assertStatus(302);

        $section = $course->sections()->first();
        $this->assertNotNull($section);
        $this->assertEquals('Chương 1: Khởi Tạo Dự Án & Thiết Kế Bảng Dữ Liệu', $section->title);

        // Add lesson with Vietnamese accents
        $lessonResponse = $this->actingAs($this->teacher)->post(
            route('teacher.sections.lessons.store', $section),
            [
                'title' => 'Bài 1: Cài đặt môi trường PHP 8.3 & Composer trên Windows và Linux',
                'type' => 'video',
                'video_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'duration' => 25,
                'content' => 'Hướng dẫn chi tiết từng bước cài đặt công cụ cần thiết cho lập trình viên.',
                'is_free_preview' => 1,
            ],
        );
        $lessonResponse->assertStatus(302);

        $lesson = $section->lessons()->first();
        $this->assertNotNull($lesson);
        $this->assertEquals('Bài 1: Cài đặt môi trường PHP 8.3 & Composer trên Windows và Linux', $lesson->title);
        $this->assertTrue($lesson->is_free_preview);

        // Submit for review
        $submitResponse = $this->actingAs($this->teacher)->post(route('teacher.courses.submit-review', $course));
        $submitResponse->assertStatus(302);

        $course->refresh();
        $this->assertEquals('pending_review', $course->status);
    }

    public function test_teacher_profile_update_with_accented_vietnamese(): void
    {
        $payload = [
            'name' => 'Nguyễn Phúc An',
            'email' => 'teacher.test@colearn.test',
            'headline' => 'Trưởng nhóm phát triển phần mềm & Giảng viên công nghệ',
            'bio' => 'Đam mê chia sẻ kiến thức công nghệ và lập trình thực chiến cho cộng đồng.',
        ];

        $response = $this->actingAs($this->teacher)->put(route('teacher.profile.update'), $payload);
        $response->assertStatus(302);

        $this->teacher->refresh();
        $this->assertEquals('Nguyễn Phúc An', $this->teacher->name);
        $this->assertEquals('Trưởng nhóm phát triển phần mềm & Giảng viên công nghệ', $this->teacher->headline);
    }

    public function test_teacher_cannot_edit_other_teacher_course(): void
    {
        $otherTeacher = User::factory()->create(['email' => 'other.teacher@colearn.test']);
        $otherTeacher->assignRole('teacher');

        $course = Course::create([
            'teacher_id' => $otherTeacher->id,
            'category_id' => $this->category->id,
            'title' => 'Khóa Học Của Giảng Viên Khác',
            'slug' => 'khoa-hoc-cua-giang-vien-khac',
            'price' => 299000,
            'level' => 'beginner',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->teacher)->get(route('teacher.courses.edit', $course));
        $response->assertStatus(403);
    }

    public function test_teacher_cannot_submit_empty_course_for_review(): void
    {
        $course = Course::create([
            'teacher_id' => $this->teacher->id,
            'category_id' => $this->category->id,
            'title' => 'Khóa Học Chưa Có Bài Giảng',
            'slug' => 'khoa-hoc-chua-co-bai-giang',
            'price' => 199000,
            'level' => 'beginner',
            'status' => 'draft',
        ]);

        $response = $this->actingAs($this->teacher)->post(route('teacher.courses.submit-review', $course));
        $response->assertStatus(302);
        $response->assertSessionHas('error');

        $course->refresh();
        $this->assertEquals('draft', $course->status);
    }

    public function test_locale_switcher_routes(): void
    {
        $response = $this->get('/lang/vi');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'vi');

        $response = $this->get('/lang/en');
        $response->assertStatus(302);
        $response->assertSessionHas('locale', 'en');
    }
}
