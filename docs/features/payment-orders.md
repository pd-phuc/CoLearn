# Feature: Payment & Orders

## Status: Completed

## Description

Hệ thống thanh toán và quản lý đơn hàng chuyên nghiệp. Hỗ trợ **Ví CoLearn (Wallet Balance)** nạp tiền → mua khóa học 1-Click, chạy song song với **VietQR (SePay)** cho chuyển khoản ngân hàng Việt Nam và **Stripe** cho thanh toán quốc tế.

## Payment Gateways

| Gateway | Provider | Phương thức | Config |
|---------|----------|------------|--------|
| **Wallet** | Internal | Trừ `user.balance` trực tiếp | — |
| **SePay / VietQR** | SePay | QR chuyển khoản → webhook tự động | Admin → Settings (hoặc `.env`) |
| **Stripe** | Stripe | Card payment → redirect | Admin → Settings (hoặc `.env`) |

> **Note:** SePay/Stripe yêu cầu admin cấu hình thông qua Admin → Settings (`/admin/settings`). Nếu chưa cấu hình, gateway bị disabled trong checkout UI.

## Models Involved
- Coupon (ULID `id`, code, discount_type, discount_value, min_order_amount)
- Order (ULID `id`, order_number, order_type: `course`/`topup`, user_id UUID, coupon_id ULID, subtotal, discount_amount, total_amount, status, payment_method, payment_id, paid_at)
- OrderItem (ULID `id`, order_id ULID, course_id ULID, price)
- Transaction (ULID `id`, user_id, order_id, amount, type: `in`/`out`, action, balance_before, balance_after)
- Enrollment
- Course
- User (student — `balance`, `total_deposit`)

## Key Features
- **Ví CoLearn (Wallet)**: Nạp tiền qua VietQR → `user.balance` tăng → mua khóa học 1-Click.
- **VietQR Modal**: Mã QR động nhúng trực tiếp trên checkout/wallet. Polling 3s tự động nhận diện thanh toán.
- **SePay Webhook**: Server-to-Server xác thực `Authorization: Bearer {API_KEY}`. Bóc tách mã đơn `ORD-...` trong nội dung CK.
- **Giỏ hàng**: Session-based (`CartService`), hỗ trợ coupon.
- **Auto-Enrollment**: Tự động ghi danh khi thanh toán thành công.
- **Transaction log**: Mọi giao dịch wallet đều được ghi log (`transactions` table).
- **Admin config**: SePay/Stripe cấu hình qua `settings` table (encrypted cho API keys).

## Routes
- `GET /cart` — Giỏ hàng
- `POST /cart/add/{course}` — Thêm khóa học
- `DELETE /cart/remove/{courseId}` — Xóa khỏi giỏ
- `POST /cart/coupon` — Áp dụng mã giảm giá
- `DELETE /cart/coupon` — Xóa mã giảm giá
- `GET /wallet` — Ví CoLearn
- `POST /wallet/topup` — Nạp tiền
- `GET /wallet/topup/{order}` — Xem đơn nạp tiền đang chờ
- `GET /checkout` — Trang checkout
- `POST /checkout` — Xử lý thanh toán
- `GET /payment/callback` — Callback từ gateway
- `POST /payment/sepay/webhook` — Webhook SePay (CSRF exempt)
- `GET /orders/{order}/status` — Polling trạng thái đơn hàng
- `GET /orders` — Lịch sử đơn hàng
- `GET /orders/{order}` — Chi tiết đơn hàng

## Services
- `SePayService` — Tạo VietQR data, đọc config từ `SettingService` → fallback `.env`
- `StripeService` — Tạo Stripe Checkout Session
- `OrderService` — Tạo/xử lý đơn hàng
- `CartService` — Quản lý giỏ hàng session
- `SettingService` — Đọc/ghi settings (Redis cache)

## Notes
- Status labels ngắn gọn: `Pending`, `Completed`, `Cancelled`, `Refunded`
- Payment method hiển thị: `sepay` → "SePay", `wallet` → "Wallet", `stripe` → "Stripe"
- Legacy `vnpay` records hiển thị là "SePay"
