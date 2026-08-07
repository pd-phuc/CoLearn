<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('email', 'teacher@colearn.test')->first() ?? User::factory()->create([
            'name' => 'Giảng Viên Mẫu',
            'email' => 'teacher@colearn.test',
        ]);

        $student = User::where('email', 'student@colearn.test')->first() ?? User::factory()->create([
            'name' => 'Học Viên Mẫu',
            'email' => 'student@colearn.test',
        ]);

        // Categories
        $categoriesData = [
            [
                'name' => 'Lập Trình Web',
                'slug' => 'lap-trinh-web',
                'description' => 'Khóa học lập trình Web từ cơ bản đến nâng cao với Laravel, React, Vue',
                'icon' => 'code-bracket',
            ],
            [
                'name' => 'C++ & Thuật Toán',
                'slug' => 'c-plus-plus-thuat-toan',
                'description' => 'Nền tảng C++ và Giải thuật lập trình cho sinh viên IT',
                'icon' => 'cpu-chip',
            ],
            [
                'name' => 'Cơ Sở Dữ Liệu',
                'slug' => 'co-so-du-lieu',
                'description' => 'Thiết kế & Tối ưu hóa Database PostgreSQL, MySQL, Redis',
                'icon' => 'server-stack',
            ],
            [
                'name' => 'DevOps & Docker',
                'slug' => 'devops-docker',
                'description' => 'Quản trị hạ tầng Docker, Nginx, CI/CD và Cloud AWS',
                'icon' => 'cloud',
            ],
        ];

        $categories = collect();
        foreach ($categoriesData as $data) {
            $categories->push(Category::firstOrCreate(['slug' => $data['slug']], $data));
        }

        // Sample Courses
        $coursesData = [
            [
                'category_slug' => 'lap-trinh-web',
                'title' => 'Lập Trình Web Laravel 13 Từ Con Số 0 Đến Real Project',
                'slug' => 'lap-trinh-web-laravel-13-tu-con-so-0-den-real-project',
                'description' => 'Khóa học hướng dẫn chi tiết xây dựng ứng dụng Web thực tế với Laravel 13, Blade SSR, Tailwind CSS 4 và Alpine.js theo mô hình Titan/28Tech.',
                'price' => 799000,
                'discount_price' => 499000,
                'level' => 'beginner',
                'status' => 'published',
                'sections' => [
                    [
                        'title' => 'Chương 1: Khởi Động Dự Án & Cấu Hình Môi Trường Docker',
                        'lessons' => [
                            ['title' => 'Bài 1: Giới thiệu tổng quan hệ thống CoLearn', 'type' => 'video', 'duration' => 600, 'is_free_preview' => true],
                            ['title' => 'Bài 2: Thiết lập Docker, PostgreSQL và Redis', 'type' => 'video', 'duration' => 900, 'is_free_preview' => true],
                            ['title' => 'Bài 3: Cấu trúc thư mục Laravel 13 & Git Workflow', 'type' => 'video', 'duration' => 750, 'is_free_preview' => false],
                        ],
                    ],
                    [
                        'title' => 'Chương 2: Xây Dựng Database & Phân Quyền RBAC',
                        'lessons' => [
                            ['title' => 'Bài 4: Thiết kế Eloquent Models và Database Migrations', 'type' => 'video', 'duration' => 1200, 'is_free_preview' => false],
                            ['title' => 'Bài 5: Phân quyền Student, Teacher và Admin với Spatie Permission', 'type' => 'video', 'duration' => 1050, 'is_free_preview' => false],
                        ],
                    ],
                ],
            ],
            [
                'category_slug' => 'c-plus-plus-thuat-toan',
                'title' => 'C++ và Cấu Trúc Dữ Liệu Thuật Toán Cho Kỳ Thi Chuyên Tin',
                'slug' => 'c-plus-plus-va-cau-truc-du-lieu-thuat-toan',
                'description' => 'Nắm chắc kiến thức C++ chuẩn, mảng, con trỏ, quy hoạch động và các giải thuật tìm kiếm tối ưu nhất.',
                'price' => 999000,
                'discount_price' => 699000,
                'level' => 'intermediate',
                'status' => 'published',
                'sections' => [
                    [
                        'title' => 'Chương 1: Cấu Trúc Điều Kiện Và Vòng Lặp Trong C++',
                        'lessons' => [
                            ['title' => 'Bài 1: Cú pháp cơ bản và kiểu dữ liệu trong C++', 'type' => 'video', 'duration' => 540, 'is_free_preview' => true],
                            ['title' => 'Bài 2: Vòng lặp For, While và Do-While', 'type' => 'video', 'duration' => 840, 'is_free_preview' => false],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($coursesData as $cData) {
            $cat = $categories->firstWhere('slug', $cData['category_slug']);

            $course = Course::firstOrCreate(
                ['slug' => $cData['slug']],
                [
                    'teacher_id' => $teacher->id,
                    'category_id' => $cat->id,
                    'title' => $cData['title'],
                    'description' => $cData['description'],
                    'price' => $cData['price'],
                    'discount_price' => $cData['discount_price'],
                    'level' => $cData['level'],
                    'status' => $cData['status'],
                ]
            );

            foreach ($cData['sections'] as $sIndex => $sData) {
                $section = Section::firstOrCreate(
                    [
                        'course_id' => $course->id,
                        'title' => $sData['title'],
                    ],
                    [
                        'sort_order' => $sIndex + 1,
                    ]
                );

                foreach ($sData['lessons'] as $lIndex => $lData) {
                    Lesson::firstOrCreate(
                        [
                            'section_id' => $section->id,
                            'slug' => Str::slug($lData['title']),
                        ],
                        [
                            'title' => $lData['title'],
                            'type' => $lData['type'],
                            'duration' => $lData['duration'],
                            'is_free_preview' => $lData['is_free_preview'],
                            'sort_order' => $lIndex + 1,
                            'video_url' => 'courses/sample.mp4',
                            'content' => 'Nội dung chi tiết bài học ' . $lData['title'],
                        ]
                    );
                }
            }

            // Enroll student to the first sample course
            Enrollment::firstOrCreate([
                'user_id' => $student->id,
                'course_id' => $course->id,
            ], [
                'status' => 'active',
                'enrolled_at' => now(),
            ]);
        }
    }
}
