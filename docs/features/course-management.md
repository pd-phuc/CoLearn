# Feature: Course Management

## Status: Planning

## Description

Quản lý khóa học — cho phép teacher tạo, chỉnh sửa khóa học với các sections và lessons. Admin duyệt khóa học trước khi publish. Student browse và tìm kiếm khóa học.

## Models Involved
- Course
- Category
- Section
- Lesson
- User (teacher)

## Key Features
- Teacher tạo khóa học (title, description, price, thumbnail, category)
- Thêm sections (chương) vào khóa học
- Thêm lessons (bài giảng) vào section — video, text, tài liệu
- Upload video/tài liệu lên AWS S3
- Course status workflow: draft → pending_review → published → archived
- Admin duyệt khóa học
- Student browse, search, filter khóa học theo category
- Trang chi tiết khóa học (curriculum, reviews, instructor info)

## Routes
### Student/Public
- `GET /courses` — Danh sách khóa học
- `GET /courses/{slug}` — Chi tiết khóa học
- `GET /categories/{slug}` — Khóa học theo danh mục

### Teacher
- `GET /teacher/courses` — Danh sách khóa học của teacher
- `GET /teacher/courses/create` — Form tạo khóa học
- `POST /teacher/courses` — Lưu khóa học mới
- `GET /teacher/courses/{id}/edit` — Form chỉnh sửa
- `PUT /teacher/courses/{id}` — Cập nhật khóa học
- `POST /teacher/courses/{id}/submit` — Submit để admin duyệt
- `POST /teacher/courses/{id}/sections` — Thêm section
- `POST /teacher/sections/{id}/lessons` — Thêm lesson

### Admin
- `GET /admin/courses` — Quản lý tất cả khóa học
- `POST /admin/courses/{id}/approve` — Duyệt khóa học
- `POST /admin/courses/{id}/reject` — Từ chối khóa học

## Notes
- Video upload xử lý qua queue (lightweight processing)
- Thumbnail tự generate hoặc teacher upload
- SEO-friendly slugs cho course URLs
