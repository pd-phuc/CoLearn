# Feature: Payment & Orders

## Status: Completed

## Description

Hệ thống thanh toán và quản lý đơn hàng song song chuyên nghiệp. Hỗ trợ **Ví CoLearn (`Wallet Balance`)** cho phép nạp tiền ➔ Mua khóa học 1-Click tức thì (0.1s), chạy song song với **Mã VietQR động nhúng trực tiếp (Zero-Redirect Modal)** cho phép quét bằng 40+ App Ngân hàng Việt Nam (Vietcombank, MB, Techcombank, MoMo,...) kèm **Server-to-Server SePay Auto-Bank Webhook (`POST /payment/sepay/webhook`)** và **VNPay IPN Webhook (`POST /payment/vnpay/ipn`)** để tự động xác nhận số tiền về tài khoản ngân hàng thực tế. Đảm bảo 100% bình đẳng dữ liệu đơn hàng, coupon, hóa đơn học viên và báo cáo doanh thu Admin giữa 2 phương thức.

## Models Involved
- Coupon (ULID `id`, code, discount_type, discount_value, min_order_amount)
- Order (ULID `id`, order_number, order_type: `course`/`topup`, user_id UUID, coupon_id ULID, subtotal, discount_amount, total_amount, status, payment_method, payment_id, paid_at)
- OrderItem (ULID `id`, order_id ULID, course_id ULID, price)
- Enrollment
- Course
- User (student - có trường `balance` lưu số dư ví)

## Key Features
- **Ví CoLearn (`Wallet Balance & Top-Up`)**: Học viên nạp tiền vào Ví (`/wallet/topup`) qua VietQR/SePay ➔ Tiền cộng trực tiếp vào `user.balance` ➔ Mua khóa học 1-Click tức thì không cần chờ chuyển khoản ngân hàng.
- **Embedded VietQR Modal**: Hiển thị mã VietQR động trực tiếp ngay trên trang Checkout và trang Nạp Tiền Ví không cần rời khỏi web. Tích hợp nút sao chép STK / Nội dung chuyển khoản 1-click & đếm ngược 15 phút.
- **Server-to-Server SePay Webhook (`POST /payment/sepay/webhook`)**: Nhận webhook tự động từ ngân hàng qua SePay với mã xác thực API Key `Authorization: Bearer {SEPAY_API_KEY}`. Tự động bóc tách mã đơn hàng `ORD-...` trong nội dung chuyển khoản, kiểm tra số tiền và tự động duyệt đơn/nạp tiền ví trong 1-3 giây!
- **Tự Động Nhận Diện Thanh Toán Real-Time**: Polling AJAX 3 giây/lần tự động kiểm tra khi ngân hàng báo tiền về ➔ Tự nhảy sang trang Hóa đơn thành công hoặc trang Ví tài khoản.
- **Giỏ Hàng Session-based (`CartService`)**: Thêm/xóa khóa học linh hoạt, tự động tính tổng tiền.
- **Mã Giảm Giá (`Coupon`)**: Cho phép áp dụng voucher giảm theo % hoặc giảm cố định số tiền.
- **Tự Động Ghi Danh (`Auto-Enrollment`)**: Tự động kích hoạt bản ghi `Enrollment` và cập nhật Order status `paid` ngay khi nhận thanh toán.
- **100% SVG Vector Icons & Light Theme**: Thiết kế hiện đại đồng bộ toàn ứng dụng.

## Routes
- `GET /cart` — Màn hình xem giỏ hàng
- `POST /cart/add/{course}` — Thêm khóa học vào giỏ hàng
- `DELETE /cart/remove/{courseId}` — Xóa khóa học khỏi giỏ hàng
- `POST /cart/coupon` — Áp dụng mã giảm giá
- `DELETE /cart/coupon` — Xóa mã giảm giá
- `GET /wallet` — Màn hình Ví CoLearn của học viên
- `POST /wallet/topup` — Khởi tạo đơn nạp tiền ví qua VietQR
- `GET /checkout` — Trang thanh toán & chọn phương thức (Ví CoLearn / VietQR / Stripe)
- `POST /checkout` — Khởi tạo đơn hàng & thanh toán 1-Click bằng Ví hoặc hiển thị VietQR Modal
- `POST /payment/sepay/webhook` — Webhook tự động từ máy chủ SePay / Ngân hàng (CSRF Exempt)
- `POST /payment/vnpay/ipn` — Webhook IPN từ server VNPay (CSRF Exempt)
- `GET /orders/{order}/status` — Real-time polling API kiểm tra trạng thái đơn hàng
- `POST /orders/{order}/simulated-pay` — Endpoint giả lập thanh toán nhanh cho môi trường Test
- `GET /orders` — Lịch sử đơn hàng của học viên
- `GET /orders/{order}` — Chi tiết hóa đơn thanh toán

