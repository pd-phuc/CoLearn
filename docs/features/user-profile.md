# Feature: User Profile & Settings

## Status: In Progress

## Description

Hệ thống Quản lý Hồ sơ cá nhân, Cập nhật thông tin, Upload ảnh đại diện Avatar, Đổi mật khẩu bảo mật và Quản lý danh sách khóa học đã đăng ký ("My Courses") dành cho tất cả các role (Student, Teacher, Admin).

## Models Involved

- User
- Course
- Enrollment
- LessonCompletion

## Key Features

- **Chỉnh sửa thông tin cá nhân**: Tên hiển thị, Số điện thoại, Tiêu đề/Headline (VD: "Senior Software Engineer"), Bio/Tiểu sử, Liên kết mạng xã hội (GitHub, LinkedIn, Facebook).
- **Upload & Cập nhật Avatar**: Tải ảnh đại diện mới từ máy tính, tự động lưu theo cấu trúc tệp chuẩn `/images/avatars/{user_id}.png`.
- **Đổi mật khẩu & Bảo mật**: Thay đổi mật khẩu hiện tại, mã hóa password an toàn với Laravel Hashing.
- **Quản lý khóa học của tôi (`My Courses`)**: Trang danh sách các khóa học đã ghi danh kèm thanh tiến độ hoàn thành bài giảng (Progress Bar) và nút "Tiếp tục học".
- **i18n Multi-language**: 100% nhãn UI, thông báo thành công/thất bại hỗ trợ Tiếng Việt & Tiếng Anh.

## Routes

- `GET /profile` — Trang cài đặt hồ sơ cá nhân (Tabbed settings UI)
- `PUT /profile` — Xử lý cập nhật thông tin cá nhân & MXH
- `POST /profile/avatar` — Xử lý upload & cập nhật ảnh đại diện Avatar
- `PUT /profile/password` — Xử lý đổi mật khẩu bảo mật
- `GET /my-courses` — Trang danh sách khóa học đã ghi danh của tôi

## Schema Extensions (users table)

- `headline` (string, nullable)
- `bio` (text, nullable)
- `phone` (string, nullable)
- `github_url` (string, nullable)
- `linkedin_url` (string, nullable)
- `facebook_url` (string, nullable)

## Notes

- Đường dẫn tệp Avatar tuân thủ định dạng chuẩn UUID `{user_id}.png`
- Mật khẩu mã hóa mặc định dùng `password_hash` (Bcrypt/Argon2)
