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
        $teacher = User::updateOrCreate(
            ['email' => 'giaolang@colearn.test'],
            [
                'id' => '019fe28b-11bc-71a4-a30c-df885963ff82',
                'name' => 'giáo.làng',
                'avatar' => '/images/avatars/019fe28b-11bc-71a4-a30c-df885963ff82.png',
            ]
        );

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

        // Real Courses from giáo.làng's YouTube channel
        $coursesData = [
            // Course 1: Giải Ngố Git-GitHub
            [
                'category_slug' => 'devops-git',
                'title' => 'Giải Ngố Git-GitHub',
                'slug' => 'giai-ngo-git-github',
                'description' => 'Khóa học Giải ngố Git-GitHub hướng dẫn chi tiết cách dùng Git, .gitignore, và đẩy code lên máy remote ngoctrinh cùng giảng viên giáo.làng.',
                'thumbnail' => 'https://img.youtube.com/vi/wFKu81ZMEcg/hqdefault.jpg',
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

            // Course 2: Giải ngố Microservices với Spring Boot
            [
                'category_slug' => 'lap-trinh-web',
                'title' => 'Giải Ngố Microservices Với Spring Boot',
                'slug' => 'giai-ngo-microservices-voi-spring-boot',
                'description' => 'Khóa học Giải ngố Microservices với Spring Boot hướng dẫn thiết kế kiến trúc hệ thống và tách nhỏ dịch vụ theo tiêu chuẩn thực tế cùng giảng viên giáo.làng.',
                'thumbnail' => 'https://img.youtube.com/vi/wFKu81ZMEcg/hqdefault.jpg',
                'learning_outcomes' => [
                    'Nắm vững kiến trúc Microservices và RESTful APIs',
                    'Thiết kế và tách dịch vụ nhỏ gọn với Spring Boot',
                    'Giao tiếp giữa các Service an toàn và tối ưu',
                ],
                'requirements' => [
                    'Kiến thức lập trình Java cơ bản',
                    'Cơ sở dữ liệu SQL cơ bản',
                ],
                'price' => 0,
                'discount_price' => null,
                'level' => 'intermediate',
                'status' => 'published',
                'sections' => [
                    [
                        'title' => 'Chương 1: Tổng Quan Microservices & Spring Boot',
                        'lessons' => [
                            [
                                'title' => 'Giải ngố Microservices: 01_Tách nhập Service thực tế',
                                'type' => 'video',
                                'duration' => 2400,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
                            [
                                'title' => 'Giải ngố Microservices: 02_Cấu hình Service Registry',
                                'type' => 'video',
                                'duration' => 1800,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
                        ],
                    ],
                ],
            ],

            // Course 3: SAP ABAP cho người mới bắt đầu
            [
                'category_slug' => 'c-plus-plus-thuat-toan',
                'title' => 'SAP ABAP Cho Người Mới Bắt Đầu',
                'slug' => 'sap-abap-cho-nguoi-moi-bat-dau',
                'description' => 'Khóa học SAP ABAP chi tiết làm quen với hệ thống SAP ERP, ngôn ngữ ABAP & SAP GUI cùng giảng viên giáo.làng.',
                'thumbnail' => 'https://img.youtube.com/vi/wFKu81ZMEcg/hqdefault.jpg',
                'learning_outcomes' => [
                    'Làm chủ kiến thức nền tảng SAP ABAP',
                    'Thao tác và lập trình báo cáo trên SAP GUI',
                    'Sẵn sàng làm việc tại các tập đoàn SAP',
                ],
                'requirements' => [
                    'Kiến thức tư duy lập trình cơ bản',
                ],
                'price' => 0,
                'discount_price' => null,
                'level' => 'beginner',
                'status' => 'published',
                'sections' => [
                    [
                        'title' => 'Chương 1: Nhập Môn SAP & ABAP Programming',
                        'lessons' => [
                            [
                                'title' => 'SAP ABAP 01: Giới thiệu hệ thống SAP ERP',
                                'type' => 'video',
                                'duration' => 2100,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
                            [
                                'title' => 'SAP ABAP 02: Cú pháp ngôn ngữ ABAP cơ bản',
                                'type' => 'video',
                                'duration' => 2700,
                                'is_free_preview' => true,
                                'video_url' => 'https://www.youtube.com/embed/wFKu81ZMEcg',
                            ],
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
                    'thumbnail' => $cData['thumbnail'] ?? null,
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
