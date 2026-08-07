# Feature: Authentication & Authorization

## Status: Planning

## Description

Hệ thống xác thực và phân quyền cho CoLearn. Hỗ trợ đăng ký/đăng nhập bằng email+password, Google OAuth, Facebook OAuth. Phân quyền dựa trên roles (student, teacher, admin) sử dụng spatie/laravel-permission.

## Models Involved
- User
- Role (spatie)
- Permission (spatie)

## Key Features
- Đăng ký tài khoản (email/password)
- Đăng nhập (email/password)
- Đăng nhập qua Google OAuth
- Đăng nhập qua Facebook OAuth
- Xác thực email (verification link)
- Quên mật khẩu / Reset password
- Phân quyền: student, teacher, admin
- Middleware bảo vệ routes theo role

## Routes
- `GET /login` — Trang đăng nhập
- `POST /login` — Xử lý đăng nhập
- `GET /register` — Trang đăng ký
- `POST /register` — Xử lý đăng ký
- `POST /logout` — Đăng xuất
- `GET /auth/google` — Redirect to Google
- `GET /auth/google/callback` — Google callback
- `GET /auth/facebook` — Redirect to Facebook
- `GET /auth/facebook/callback` — Facebook callback
- `GET /email/verify/{id}/{hash}` — Verify email
- `GET /forgot-password` — Form quên mật khẩu
- `POST /forgot-password` — Gửi link reset
- `GET /reset-password/{token}` — Form reset
- `POST /reset-password` — Xử lý reset

## Packages
- `laravel/sanctum`
- `laravel/socialite`
- `spatie/laravel-permission`

## Notes
- Sanctum SPA cookie-based cho web, token-based cho future API
- Mặc định user mới đăng ký có role "student"
- Teacher role cần được admin cấp hoặc qua form "Đăng ký giảng viên"
