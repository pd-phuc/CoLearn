# 🎓 CoLearn — Nền Tảng Học & Bán Khóa Học Trực Tuyến

> **CoLearn** là nền tảng đào tạo lập trình và kỹ năng trực tuyến (mô hình đơn vị đào tạo theo phong cách TITV / 28Tech). Hệ thống hỗ trợ đa vai trò (Student, Teacher, Admin), tích hợp cổng thanh toán tự động **SePay (VietQR Auto-Bank Webhook)**, **Stripe**, **Ví CoLearn 1-Click**, cùng trình phát học tập chuyên nghiệp và hệ thống quản trị toàn diện.

---

## 📑 Mục Lục
1. [Công Nghệ & Kiến Trúc](#-công-nghệ--kiến-trúc)
2. [Phân Hệ Chức Năng (Core Features)](#-phân-hệ-chức-năng-core-features)
3. [Tài Khoản Mẫu (Demo Credentials)](#-tài-khoản-mẫu-demo-credentials)
4. [Yêu Cầu Hệ Thống (Prerequisites)](#-yêu-cầu-hệ-thống-prerequisites)
5. [Hướng Dẫn Cài Đặt (Installation Guide)](#-hướng-dẫn-cài-đặt-installation-guide)
   - [Cách 1: Chạy bằng Docker (Khuyến nghị chuẩn nhất)](#cách-1-chạy-bằng-docker-khuyến-nghị-chuẩn-nhất)
   - [Cách 2: Chạy trực tiếp trên máy Local](#cách-2-chạy-trực-tiếp-trên-máy-local)
6. [Danh Mục Lệnh (Useful Commands)](#-danh-mục-lệnh-useful-commands)
7. [Cấu Trúc Thư Mục (Project Structure)](#-cấu-trúc-thư-mục-project-structure)
8. [Cấu Hình & Tích Hợp (Payment & Services)](#-cấu-hình--tích-hợp-payment--services)

---

## 🛠 Công Nghệ & Kiến Trúc

### Backend
- **Framework**: Laravel 13.x (PHP 8.3+)
- **Database**: PostgreSQL 16
- **Cache & Queue**: Redis 7
- **Architecture**: Service Layer Pattern, Eloquent ORM, Form Requests, Thin Controllers
- **Authentication**: Laravel Sanctum (SPA Cookie Auth cho Web + Token cho Mobile API)
- **Authorization**: `spatie/laravel-permission` (RBAC: Admin, Teacher, Student)
- **Social Login**: Laravel Socialite (Google OAuth 2.0, Facebook Login)

### Frontend
- **Template Engine**: Blade Templates (SSR)
- **Styling**: Tailwind CSS v4 (CSS-first architecture)
- **Reactivity**: Alpine.js (Lightweight UI interactions)
- **Bundler**: Vite 8.x
- **Internationalization**: Hỗ trợ 2 ngôn ngữ song ngữ Tiếng Việt 🇻🇳 và English 🇬🇧 (`/lang/{locale}`)

### Infrastructure & Services
- **Containerization**: Docker & Docker Compose (PHP-FPM, Nginx, PostgreSQL, Redis)
- **Payment Gateways**:
  - **SePay**: Nhận diện chuyển khoản tự động qua VietQR Auto-Bank Webhook (3–5s khớp đơn)
  - **Stripe**: Cổng thẻ thanh toán quốc tế (Visa, MasterCard, JCB)
  - **CoLearn Wallet**: Ví điện tử nội bộ mua khóa học tức thì 1-Click
- **Cloud Storage**: AWS S3 (video bài giảng, thumbnail, tài liệu)
- **Email**: Mailgun / SMTP

---

## 🎯 Phân Hệ Chức Năng (Core Features)

### 1. 👨‍🎓 Học Viên (Student Portal)
- **Khám phá khóa học**: Tìm kiếm, lọc theo danh mục, mức độ (Mới bắt đầu, Trung cấp, Chuyên sâu), sắp xếp theo giá và độ phổ biến.
- **Chi tiết khóa học**: Xem lộ trình chương/bài, bài học thử miễn phí (Free Preview), thông tin giảng viên.
- **Giỏ hàng & Khuyến mãi**: Thêm/xóa giỏ hàng, áp dụng mã giảm giá Coupon tính toán chiết khấu trực tiếp.
- **Ví CoLearn (`/wallet`)**: Nạp tiền qua mã QR SePay, theo dõi số dư và chi tiết lịch sử biến động số dư.
- **Thanh toán linh hoạt (`/checkout`)**: 
  - Mua 1-Click qua số dư Ví CoLearn.
  - Quét mã QR SePay tự động kích hoạt khóa học ngay khi nhận tiền.
  - Thanh toán thẻ tín dụng quốc tế qua Stripe.
- **Trình phát học tập (`/learn/{slug}/{lesson}`)**:
  - Xem video (YouTube / MP4 / S3) với ghi chú, nội dung bài học Markdown.
  - Đánh dấu hoàn thành bài học, tự động tính % tiến độ toàn khóa học.
- **Lịch sử đơn hàng & Hóa đơn**: Xem lại chi tiết đơn hàng và xuất hóa đơn điện tử.

### 2. 👨‍🏫 Giảng Viên (Teacher Portal — `/teacher`)
- **Teacher Dashboard**: Thống kê số lượng học viên, tổng khóa học, doanh thu ước tính và bảng học viên mới.
- **Quản lý khóa học (`/teacher/courses`)**:
  - Tạo mới, cập nhật giá và giá giảm với ô nhập định dạng tiền tệ chuyên nghiệp (`<x-money-input>`).
  - Soạn thảo danh sách mục tiêu đạt được (Learning Outcomes).
  - Soạn thảo cấu trúc Chương (Sections) và Bài học (Lessons: Video, Text, Thời lượng, Cho phép học thử).
  - Gửi khóa học lên Admin phê duyệt (`Draft` ➔ `Pending Review`).
- **Quản lý học viên (`/teacher/students`)**: Theo dõi danh sách học viên đang theo học từng khóa, ngày ghi danh và tiến độ.
- **Báo cáo & Phân tích (`/teacher/analytics`)**: Biểu đồ doanh thu 12 tháng, số lượt ghi danh theo thời gian.
- **Hồ sơ giảng viên (`/teacher/profile`)**: Cập nhật thông tin tiểu sử, chức danh, liên kết mạng xã hội, ảnh đại diện và mật khẩu.

### 3. 👨‍💼 Quản Trị Viên (Admin Panel — `/admin`)
- **Admin Dashboard**: Biểu đồ doanh thu tổng hệ thống, lượng học viên mới, tổng đơn hàng và danh sách khóa học chờ duyệt.
- **Phê duyệt khóa học (`/admin/courses`)**: Xem trước nội dung chi tiết, duyệt phát hành (`Published`) hoặc từ chối (`Rejected` kèm lý do phản hồi).
- **Quản lý người dùng (`/admin/users`)**: Xem danh sách, gán vai trò, khóa/mở khóa tài khoản (Ban/Unban), điều chỉnh số dư ví thủ công (+/- tiền kèm lý do).
- **Quản lý đơn hàng & Hoàn tiền (`/admin/orders`)**: Tra cứu đơn hàng, thực hiện hoàn tiền (Refund) tự động hoàn trả số dư về ví học viên.
- **Quản lý danh mục (`/admin/categories`)**: Thêm, sửa, sắp xếp các danh mục khóa học.
- **Quản lý mã giảm giá (`/admin/coupons`)**: Tạo mã Coupon giảm %, giảm tiền mặt cố định, giới hạn lượt dùng và ngày hết hạn.
- **Nhật ký giao dịch (`/admin/transactions`)**: Giám sát toàn bộ dòng tiền nạp, rút, mua khóa học và hoàn tiền trong hệ thống.
- **Cài đặt hệ thống (`/admin/settings`)**: Cấu hình trực tiếp trên Web: thông tin SePay (Ngân hàng, STK, Secret Key), Stripe, Mail SMTP, Google/Facebook OAuth, AWS S3.

---

## 🔑 Tài Khoản Mẫu (Demo Credentials)

Sau khi chạy lệnh khởi tạo dữ liệu mẫu, hệ thống cung cấp sẵn các tài khoản sau:

| Vai trò (Role) | Email Đăng Nhập | Mật Khẩu (Password) | URL Truy Cập |
| :--- | :--- | :--- | :--- |
| **Quản trị viên (Admin)** | `admin@colearn.test` | `password` | `http://localhost/admin` |
| **Giảng viên (Teacher)** | `teacher@colearn.test` | `password` | `http://localhost/teacher` |
| **Học viên (Student)** | `student@colearn.test` | `password` | `http://localhost/login` |

---

## 💻 Yêu Cầu Hệ Thống (Prerequisites)

- **Docker Desktop** (Khuyến nghị cho cả Windows, macOS, Linux)
- **Node.js** >= 20.x & **npm** (để build assets giao diện)
- **Composer** (để chạy các lệnh script tiện ích)

---

## 🚀 Hướng Dẫn Cài Đặt (Installation Guide)

### Cách 1: Chạy bằng Docker (Khuyến nghị chuẩn nhất)

#### Bước 1: Clone mã nguồn dự án
```bash
git clone <repository-url> CoLearn
cd CoLearn
```

#### Bước 2: Khởi tạo file cấu hình môi trường `.env`
- Trên **Windows (PowerShell)**:
  ```powershell
  copy .env.example .env
  ```
- Trên **Linux / macOS**:
  ```bash
  cp .env.example .env
  ```

#### Bước 3: Khởi động các dịch vụ Docker
```bash
docker compose up -d
```
*(Lệnh này sẽ khởi chạy 4 container: `colearn-nginx`, `colearn-app`, `colearn-postgres`, `colearn-redis`)*

#### Bước 4: Khởi tạo Database & Dữ liệu mẫu (Chỉ chạy 1 lần đầu tiên)
```bash
composer docker:init
```
> **Lưu ý**: Lệnh này sẽ tự động sinh App Key, tạo storage link, chạy migrate toàn bộ bảng và nạp dữ liệu mẫu vào PostgreSQL. Database được lưu trữ vĩnh viễn trong Docker volume `postgres_data`, các lần sau khởi động máy bạn **không cần chạy lại lệnh này**.

#### Bước 5: Biên dịch giao diện Frontend
```bash
npm install
npm run build
```

#### Bước 6: Truy cập ứng dụng
Mở trình duyệt và truy cập: **`http://localhost`**

---

### Cách 2: Chạy trực tiếp trên máy Local (Không dùng Docker)

Nếu máy bạn đã có sẵn PHP 8.3, PostgreSQL/MySQL và Redis:

```bash
# 1. Cài đặt dependencies PHP
composer install

# 2. Tạo file .env và sinh key
copy .env.example .env
php artisan key:generate
php artisan storage:link

# 3. Chạy migration và seed dữ liệu
php artisan migrate --seed

# 4. Cài đặt và build assets
npm install
npm run build

# 5. Khởi chạy dev server
composer dev
# Hoặc chạy riêng: php artisan serve
```

---

## 🛠 Danh Mục Lệnh (Useful Commands)

Các lệnh shortcut được tích hợp sẵn trong `composer.json` để thao tác nhanh:

| Lệnh (Command) | Chức Năng Chi Tiết |
| :--- | :--- |
| `composer docker:init` | Khởi tạo app key, storage link, migrate fresh và nạp dữ liệu seed vào Docker |
| `composer docker:seed` | Nạp lại dữ liệu mẫu vào Docker database |
| `composer docker:test` | Chạy toàn bộ bộ kiểm thử tự động (Feature/Unit tests) trong Docker |
| `composer test` | Chạy test suite bằng PHPUnit nội bộ |
| `composer lint` | Kiểm tra định dạng code chuẩn Laravel Pint |
| `composer format` | Tự động format code (Laravel Pint + Prettier Blade) |
| `npm run build` | Biên dịch toàn bộ CSS/JS cho môi trường Production (Vite) |
| `docker compose up -d` | Bật toàn bộ các dịch vụ Docker ngầm |
| `docker compose down` | Dừng các container Docker |

---

## 📁 Cấu Trúc Thư Mục (Project Structure)

```
CoLearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Quản lý khóa học, người dùng, đơn hàng, settings
│   │   │   ├── Auth/           # Đăng nhập, đăng ký, quên mật khẩu, Socialite OAuth
│   │   │   ├── Student/        # Giỏ hàng, checkout, ví, học tập, SePay webhook
│   │   │   ├── Teacher/        # Dashboard, tạo khóa học, bài học, học viên, analytics
│   │   │   ├── CourseCatalogController.php
│   │   │   └── CourseDetailController.php
│   │   └── Middleware/
│   ├── Models/                 # User, Course, Section, Lesson, Order, Transaction, Coupon...
│   ├── Notifications/          # Email đặt lại mật khẩu, thông báo
│   └── Services/
│       ├── CartService.php     # Logic giỏ hàng & coupon
│       ├── OrderService.php    # Xử lý thanh toán ví, duyệt đơn & ghi danh
│       ├── SePayService.php    # Tích hợp SePay QR, xác thực webhook & tự động khớp đơn
│       ├── SettingService.php  # Quản lý cấu hình hệ thống dynamic trong DB
│       └── StripeService.php   # Tích hợp cổng thanh toán Stripe
├── database/
│   ├── factories/              # User, Course, Category factories
│   ├── migrations/             # Migration schema (UUID, ULID, Indexes)
│   └── seeders/                # RoleAndPermissionSeeder, CourseSeeder
├── docker/
│   ├── nginx/default.conf      # Cấu hình Nginx reverse proxy
│   └── php/Dockerfile          # Dockerfile PHP 8.3 FPM + Extensions
├── lang/
│   ├── en/messages.php         # Bản dịch Tiếng Anh
│   └── vi/messages.php         # Bản dịch Tiếng Việt
├── resources/
│   ├── css/app.css             # Tailwind CSS v4 entry
│   ├── js/app.js               # Alpine.js entry
│   └── views/
│       ├── admin/              # Giao diện quản trị Admin
│       ├── auth/               # Giao diện đăng nhập, đăng ký, quên mật khẩu
│       ├── checkout/           # Giao diện thanh toán đơn hàng
│       ├── components/         # Blade components: <x-sepay-modal>, <x-money-input>, <x-stat-card>...
│       ├── courses/            # Danh mục & chi tiết khóa học
│       ├── layouts/            # Base layouts (app, admin, teacher, student)
│       ├── learn/              # Trình phát video & học tập
│       ├── teacher/            # Giao diện giảng viên
│       └── wallet/             # Giao diện Ví CoLearn & nạp tiền
├── routes/
│   ├── web.php                 # Web routes (Session-based + Role middleware)
│   └── api.php                 # API routes (Sanctum token)
├── tests/
│   ├── Feature/
│   │   ├── SePayPaymentTest.php    # Kiểm thử SePay QR & Webhook nạp ví/mua khóa học
│   │   └── TeacherPortalTest.php   # Kiểm thử quyền và chức năng giảng viên
│   └── Unit/
├── docker-compose.yml          # Docker Compose (App, Nginx, Postgres, Redis)
└── composer.json
```

---

## ⚙️ Cấu Hình & Tích Hợp (Payment & Services)

### 1. Cấu hình SePay (Chuyển khoản ngân hàng tự động)
Trong file `.env` hoặc tại trang Quản trị viên (`/admin/settings ➔ SePay`):
```env
SEPAY_API_KEY=YOUR_SEPAY_API_KEY
SEPAY_BANK_ID=MBBank               # Mã ngân hàng (MBBank, VCB, ACB, Techcombank, NCB...)
SEPAY_ACCOUNT_NO=0123456789        # Số tài khoản ngân hàng nhận tiền
SEPAY_ACCOUNT_NAME="CONG TY ABC"   # Tên chủ tài khoản
```
- **Webhook URL**: Cấu hình trên Dashboard của SePay trỏ về endpoint:
  ```
  https://your-domain.com/payment/sepay/webhook
  ```
- **Xác thực**: Webhook tự động kiểm tra header `Authorization: Apikey {SEPAY_API_KEY}` và tự động kích hoạt đơn hàng / cộng tiền ví ngay khi tiền về tài khoản.

### 2. Cấu hình Đăng nhập Mạng Xã hội (Socialite)
```env
# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
GOOGLE_REDIRECT_URI="${APP_URL}/auth/google/callback"

# Facebook OAuth
FACEBOOK_CLIENT_ID=your-facebook-app-id
FACEBOOK_CLIENT_SECRET=your-facebook-app-secret
FACEBOOK_REDIRECT_URI="${APP_URL}/auth/facebook/callback"
```

### 3. Cấu hình Email gửi thông báo (SMTP / Mailgun)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="support@colearn.test"
MAIL_FROM_NAME="CoLearn Academy"
```

---

## 📄 License & Tác Quyền
Dự án được xây dựng và phát triển dưới giấy phép [MIT License](LICENSE).
Mọi thắc mắc và đóng góp vui lòng mở Issue hoặc gửi Pull Request trên repository.
