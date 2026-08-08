# Feature: Learning Experience

## Status: Completed

## Description

Trải nghiệm học tập cho student — xem video HTML5/YouTube embed, đọc tài liệu, theo dõi tiến độ, đánh dấu hoàn thành bài học, và điều hướng bài giảng mượt mà. Giao diện Player Udemy-style tối ưu trải nghiệm học tập không xao nhãng.

## Models Involved
- Enrollment
- Course
- Section
- Lesson
- LessonCompletion
- User (student)

## Key Features
- **Light Theme UI Standard**: Giao diện sáng chủ đạo đồng bộ toàn hệ thống (`bg-slate-100`, `bg-white`, `text-slate-900`), khung Player video bo góc hiện đại.
- **Learning Player Window**: Xem video bài giảng (HTML5 Video Player / YouTube embed / Vimeo) hoặc đọc tài liệu text bài học.
- **Custom Thin Scrollbars**: Thanh cuộn tùy chỉnh mỏng nhẹ mượt mà (`::-webkit-scrollbar`), loại bỏ thanh cuộn trắng mặc định của trình duyệt.
- **Header Navigation & Sidebar Toggle (☰)**: Nút ☰ ở góc phải cho phép đóng/mở khung danh sách bài học linh hoạt trên mọi màn hình.
- **Đánh dấu hoàn thành Lesson (`LessonCompletion`)**: Nút bấm rõ ràng nhãn Tiếng Việt *"Đánh dấu đã học xong"* / *"Đã hoàn thành"* kèm icon checkmark ➔ Phản ứng AJAX tức thì, tự động đổi màu xanh lá và cập nhật icon ở danh sách bài học bên phải.
- **Clean Progress Badge**: Badge hiển thị tiến độ học tập dạng % thanh thoát trên thanh Header bar.
- **Chuyển Bài Học Tiếp Theo**: Nút *"Bài tiếp theo"* tự động chuyển sang bài tiếp theo trong giáo trình.
- **100% SVG Vector Icons**: Tuân thủ tiêu chuẩn giao diện Production, 0% emoji text.

## Routes
- `GET /my-courses` — Dashboard danh sách khóa học đang học của tôi
- `GET /learn/{course:slug}/{lesson:id?}` — Player học tập trực tuyến
- `POST /learn/lessons/{lesson}/toggle-complete` — Toggle trạng thái hoàn thành bài học (AJAX/JSON)

## Notes
- Video stream linh hoạt hỗ trợ tệp MP4, YouTube Unlisted, Vimeo
- Quyền truy cập bài học được bảo vệ: Yêu cầu ghi danh (`Enrollment`) hoặc bài học thuộc dạng `is_free_preview`
- Tích hợp mượt mà với Alpine.js AJAX update progress % mà không cần reload trang

