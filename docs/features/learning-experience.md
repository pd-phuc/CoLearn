# Feature: Learning Experience

## Status: Planning

## Description

Trải nghiệm học tập cho student — xem video, đọc tài liệu, theo dõi tiến độ, hoàn thành khóa học. Giao diện learning player tương tự Udemy.

## Models Involved
- Enrollment
- Course
- Section
- Lesson
- LessonCompletion
- User (student)

## Key Features
- Learning player — xem video bài giảng
- Sidebar curriculum — danh sách sections/lessons
- Đánh dấu hoàn thành lesson
- Progress tracking — % hoàn thành khóa học
- Tự động chuyển lesson tiếp theo
- Download tài liệu đính kèm
- Email chúc mừng khi hoàn thành khóa học
- Dashboard student — danh sách khóa học đang học

## Routes
- `GET /my-courses` — Dashboard khóa học đang học
- `GET /learn/{course}/{lesson?}` — Learning player
- `POST /learn/{lesson}/complete` — Đánh dấu hoàn thành
- `GET /learn/{lesson}/download/{material}` — Download tài liệu

## Notes
- Video stream từ S3 (signed URLs để bảo vệ)
- Progress lưu vào bảng lesson_completions
- Cần kiểm tra enrollment trước khi cho truy cập nội dung
- Gửi email congratulations khi 100% lessons completed (via queue)
