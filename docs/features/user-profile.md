# Feature: User Profile & Settings

## Status: Completed

## Description

Hệ thống Quản lý Hồ sơ cá nhân, Cập nhật thông tin, Upload ảnh đại diện Avatar tự động, Đổi mật khẩu qua Email xác thực an toàn (`[CoLearn] Password Reset Verification`), và Quản lý danh sách khóa học đã đăng ký ("My Courses") dành cho tất cả các role (Student, Teacher, Admin).

## Models Involved

- User
- Course
- Enrollment
- LessonCompletion

## Key Features

- **Chỉnh sửa thông tin cá nhân**: Tên hiển thị, Số điện thoại, Tiêu đề/Headline (VD: "Senior Software Engineer"), Bio/Tiểu sử, Liên kết mạng xã hội (GitHub, LinkedIn, Facebook).
- **Upload & Cập nhật Avatar Tự Động**: Tải ảnh đại diện mới qua nút bấm màu cam tối ưu UX (chọn ảnh tự động submit form), tự động lưu tệp chuẩn UUID `/images/avatars/{uuid}.jpg`.
- **Đổi mật khẩu & Bảo mật qua Email**: Đổi mật khẩu an toàn thông qua liên kết xác thực gửi về Email (`[CoLearn] Password Reset Verification`), không nhập mật khẩu cũ/mới trực tiếp tại form. Tự động bảo lưu Email từ trang Đăng nhập sang trang Quên mật khẩu.
- **Tiêu chuẩn UI Production**: 100% Biểu tượng dùng chuẩn Vector SVG (loại bỏ toàn bộ Unicode/Emoji text), Placeholder chuẩn hóa i18n (`name@example.com`), Thông báo lỗi xác thực tối giản (`Invalid email or password.`).
- **Quản lý khóa học của tôi (`My Courses`)**: Trang danh sách các khóa học đã ghi danh kèm thanh tiến độ hoàn thành bài giảng (Progress Bar) và nút "Tiếp tục học".
- **i18n Multi-language**: 100% nhãn UI, thông báo hỗ trợ Tiếng Việt & Tiếng Anh (`__('key')`).

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
