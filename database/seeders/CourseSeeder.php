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
        $teacher = User::where('email', 'giaolang@colearn.test')->first() ?? User::factory()->create([
            'name' => 'giáo.làng',
            'email' => 'giaolang@colearn.test',
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
                'name' => 'DevOps & Git',
                'slug' => 'devops-git',
                'description' => 'Quản trị hạ tầng Docker, Git, Nginx, CI/CD và Cloud AWS',
                'icon' => 'cloud',
            ],
        ];

        $categories = collect();
        foreach ($categoriesData as $data) {
            $categories->push(Category::firstOrCreate(['slug' => $data['slug']], $data));
        }

        // Sample Courses
        $coursesData = [
            // Real YouTube Course: Giải ngố Git-GitHub (giáo.làng)
            [
                'category_slug' => 'devops-git',
                'title' => 'Giải Ngố Git-GitHub',
                'slug' => 'giai-ngo-git-github',
                'description' => 'Khóa học Giải ngố Git-GitHub hướng dẫn chi tiết cách dùng Git, .gitignore, và đẩy code lên máy remote ngoctrinh cùng giảng viên giáo.làng.',
                'learning_outcomes' => [
                    'Hiểu rõ tư duy quản lý mã nguồn với Git và GitHub',
                    'Thành thạo cấu hình file .gitignore cho dự án thực tế',
                    'Bản lĩnh đẩy code lên máy remote ngoctrinh an toàn',
                    'Xử lý xung đột code (Merge Conflict) tự tin và bài bản',
                ],
                'requirements' => [
                    'Máy tính cài đặt sẵn Git CLI hoặc VS Code',
                    'Kiến thức sử dụng máy tính cơ bản',
                ],
                'price' => 0,
                'discount_price' => null,
                'level' => 'beginner',
                'status' => 'published',
                'sections' => [
                    [
                        'title' => 'Chương 1: Nền Tảng Git & GitHub Thực Chiến',
                        'lessons' => [
                            [
                                'title' => 'Giải ngố Git-GitHub: 01_Git-GitHub là gì, bắt đầu từ đâu?',
                                'type' => 'video',
                                'duration' => 5449,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
                            [
                                'title' => 'Giải ngố Git-GitHub: 02_.gitignore là gì?',
                                'type' => 'video',
                                'duration' => 3036,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
                            [
                                'title' => 'Giải ngố Git-GitHub: 03_Đẩy code lên máy remote ngoctrinh',
                                'type' => 'video',
                                'duration' => 1946,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
                        ],
                    ],
                ],
            ],
            // Sample Course 2: Laravel 13
            [
                'category_slug' => 'lap-trinh-web',
                'title' => 'Lập Trình Web Laravel 13 Từ Con Số 0 Đến Real Project',
                'slug' => 'lap-trinh-web-laravel-13-tu-con-so-0-den-real-project',
                'description' => 'Khóa học hướng dẫn chi tiết xây dựng ứng dụng Web thực tế với Laravel 13, Blade SSR, Tailwind CSS 4 và Alpine.js theo mô hình Titan/28Tech.',
                'learning_outcomes' => [
                    'Nắm vững tư duy lập trình thực chiến chuẩn 28Tech',
                    'Xây dựng dự án thực tế từ đầu đến triển khai Production',
                    'Tối ưu hóa hiệu năng Database & Caching Redis',
                    'Nhận chứng chỉ hoàn thành xuất sắc từ CoLearn',
                ],
                'requirements' => [
                    'Có máy tính kết nối Internet',
                    'Hiểu biết cơ bản về PHP hoặc lập trình cơ bản',
                ],
                'price' => 799000,
                'discount_price' => 499000,
                'level' => 'beginner',
                'status' => 'published',
                'sections' => [
                    [
                        'title' => 'Chương 1: Khởi Động Dự Án & Cấu Hình Môi Trường Docker',
                        'lessons' => [
                            ['title' => 'Bài 1: Giới thiệu tổng quan hệ thống CoLearn', 'type' => 'video', 'duration' => 600, 'is_free_preview' => true, 'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg'],
                            ['title' => 'Bài 2: Thiết lập Docker, PostgreSQL và Redis', 'type' => 'video', 'duration' => 900, 'is_free_preview' => true, 'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg'],
                            ['title' => 'Bài 3: Cấu trúc thư mục Laravel 13 & Git Workflow', 'type' => 'video', 'duration' => 750, 'is_free_preview' => false, 'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg'],
                        ],
                    ],
                    [
                        'title' => 'Chương 2: Xây Dựng Database & Phân Quyền RBAC',
                        'lessons' => [
                            ['title' => 'Bài 4: Thiết kế Eloquent Models và Database Migrations', 'type' => 'video', 'duration' => 1200, 'is_free_preview' => false, 'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg'],
                            ['title' => 'Bài 5: Phân quyền Student, Teacher và Admin với Spatie Permission', 'type' => 'video', 'duration' => 1050, 'is_free_preview' => false, 'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($coursesData as $cData) {
            $cat = $categories->firstWhere('slug', $cData['category_slug']);

            $course = Course::updateOrCreate(
                ['slug' => $cData['slug']],
                [
                    'teacher_id' => $teacher->id,
                    'category_id' => $cat->id,
                    'title' => $cData['title'],
                    'description' => $cData['description'],
                    'learning_outcomes' => $cData['learning_outcomes'] ?? [],
                    'requirements' => $cData['requirements'] ?? [],
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
                            'video_url' => $lData['video_url'],
                            'content' => 'Nội dung bài học ' . $lData['title'],
                        ]
                    );
                }
            }

            // Enroll student to the course
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
