# Feature: Reviews & Ratings

## Status: Planning

## Description

Hệ thống đánh giá khóa học — student có thể viết review và cho điểm (1-5 sao) sau khi enrolled.

## Models Involved
- Review
- Course
- User (student)

## Key Features
- Student viết review (rating 1-5 + comment)
- Chỉ student đã enrolled mới được review
- Mỗi student chỉ review 1 lần / khóa học (có thể edit)
- Hiển thị average rating trên course card
- Danh sách reviews trên trang chi tiết khóa học
- Admin có thể xóa review vi phạm

## Routes
- `POST /courses/{course}/reviews` — Tạo review
- `PUT /reviews/{review}` — Sửa review
- `DELETE /reviews/{review}` — Xóa review (admin)

## Notes
- Average rating cache trong bảng courses (computed column hoặc observer)
- Pagination cho danh sách reviews
