# Feature: Payment & Orders

## Status: Planning

## Description

Hệ thống thanh toán và quản lý đơn hàng. Hỗ trợ VNPay (Việt Nam) và Stripe (quốc tế). Student mua khóa học → tạo Order → thanh toán → enrollment tự động.

## Models Involved
- Order
- OrderItem
- Enrollment
- Course
- Coupon
- User (student)

## Key Features
- Giỏ hàng (cart) — thêm/xóa khóa học
- Áp dụng mã giảm giá (Coupon)
- Checkout → chọn phương thức thanh toán
- Thanh toán VNPay (chuyển khoản, ATM, QR)
- Thanh toán Stripe (Visa/Mastercard)
- Payment callback/webhook xử lý
- Tự động tạo Enrollment khi Order paid
- Gửi email xác nhận đơn hàng
- Lịch sử đơn hàng
- Admin quản lý orders, xử lý refund

## Routes
- `GET /cart` — Xem giỏ hàng
- `POST /cart/add/{course}` — Thêm vào giỏ
- `DELETE /cart/remove/{course}` — Xóa khỏi giỏ
- `POST /cart/coupon` — Áp dụng mã giảm giá
- `GET /checkout` — Trang thanh toán
- `POST /checkout` — Xử lý thanh toán
- `GET /payment/vnpay/return` — VNPay callback
- `POST /payment/stripe/webhook` — Stripe webhook
- `GET /orders` — Lịch sử đơn hàng
- `GET /orders/{id}` — Chi tiết đơn hàng

## Notes
- Cart có thể dùng session (guest) hoặc database (logged in)
- VNPay cần IPN URL cho server-to-server callback
- Stripe webhook cần signature verification
- Order status: pending → paid → refunded
