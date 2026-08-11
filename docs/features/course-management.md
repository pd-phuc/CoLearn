# Feature: Course Management

## Status: Partially Implemented

## Description

Quản lý khóa học — teacher tạo, chỉnh sửa khóa học với sections và lessons. Admin duyệt khóa học trước khi publish. Student browse và tìm kiếm khóa học.

## Models Involved
- Course (ULID, status: draft/pending_review/published/archived)
- Category (ULID, sort_order)
- Section (ULID, belongs to Course)
- Lesson (ULID, belongs to Section, type: video/text/document)
- LessonCompletion (student progress tracking)
- User (teacher role)

## What's Implemented

### Student/Public ✅
- `GET /courses` — Danh sách khóa học (search, filter by category)
- `GET /courses/{slug}` — Chi tiết khóa học (curriculum, instructor, purchase card)

### Admin (Course Approval) ✅
- `GET /admin/courses` — List all courses with status/search filter
- `GET /admin/courses/{course}` — View course detail (sections/lessons)
- `POST /admin/courses/{course}/approve` — Approve → `published`
- `POST /admin/courses/{course}/reject` — Reject → `draft` + reason

### Not Yet Implemented
- Teacher course creation/editing UI
- Teacher section/lesson builder
- Video upload to S3
- Submit for review workflow (teacher → admin)

## Course Status Workflow
```
draft → pending_review → published → archived
```
- Teacher creates course → status: `draft`
- Teacher submits → status: `pending_review`
- Admin approves → status: `published` (sets `reviewed_at`, `reviewed_by`)
- Admin rejects → status: `draft` (sets `rejection_reason`)

## Database Fields (courses table)
- `title`, `slug`, `description`, `learning_outcomes` (JSON), `requirements` (JSON)
- `price`, `discount_price`, `thumbnail`, `level`
- `status`: draft/pending_review/published/archived
- `rejection_reason` (text, nullable) — admin rejection note
- `reviewed_at` (timestamp) — when admin reviewed
- `reviewed_by` (string, user ULID) — which admin reviewed
- `is_featured` (boolean) — admin can feature courses

## Notes
- SEO-friendly slugs for course URLs
- Video upload xử lý qua queue
- Category has `sort_order` field for admin reordering
