# Feature: Admin Panel

## Status: Planning

## Description

Bảng điều khiển quản trị cho admin — quản lý users, courses, orders, categories, coupons. Dashboard tổng quan với thống kê.

## Models Involved
- User
- Course
- Order
- Category
- Coupon
- Enrollment

## Key Features
- Dashboard — tổng quan doanh thu, số user, số khóa học
- Quản lý Users — CRUD, gán role, ban/unban
- Quản lý Courses — duyệt/từ chối, archive
- Quản lý Categories — CRUD
- Quản lý Orders — xem, refund
- Quản lý Coupons — CRUD, set expiry, usage limit
- Thống kê — doanh thu theo tháng, top courses, top teachers

## Routes
- `GET /admin` — Dashboard
- `GET /admin/users` — Quản lý users
- `GET /admin/courses` — Quản lý courses
- `GET /admin/orders` — Quản lý orders
- `GET /admin/categories` — Quản lý categories
- `GET /admin/coupons` — Quản lý coupons
- `GET /admin/analytics` — Thống kê

## Notes
- Tất cả admin routes bảo vệ bằng role middleware
- DataTables hoặc Livewire cho danh sách lớn
- Charts cho dashboard analytics (Chart.js hoặc ApexCharts)
