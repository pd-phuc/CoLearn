# CoLearn — Online Course & Learning Platform

> **CoLearn** is an online education and course platform built with Laravel and modern web technologies. The system supports three user roles (Student, Teacher, Admin), and integrates automated payment processing via **SePay (VietQR Auto-Bank Webhook)**, **Stripe**, **CoLearn Wallet (1-Click Buy)**, a dedicated video learning player, and a comprehensive administration dashboard.

---

## Table of Contents
1. [Tech Stack & Architecture](#tech-stack--architecture)
2. [Core Features](#core-features)
3. [Demo Accounts](#demo-accounts)
4. [Prerequisites](#prerequisites)
5. [Installation](#installation)
6. [Useful Commands](#useful-commands)
7. [Directory Structure](#directory-structure)
8. [Services & Payment Configuration](#services--payment-configuration)
9. [License](#license)

---

## Tech Stack & Architecture

### Backend
- **Framework**: Laravel 13.x (PHP 8.3+)
- **Database**: PostgreSQL 16
- **Cache & Queue**: Redis 7
- **Architecture**: Service Layer Pattern, Form Requests, Thin Controllers, Eloquent ORM
- **Authentication**: Laravel Sanctum (SPA Cookie Auth for Web, Token Auth for Mobile API)
- **Authorization**: `spatie/laravel-permission` (RBAC: Admin, Teacher, Student)
- **OAuth Social Login**: Laravel Socialite (Google, Facebook)

### Frontend
- **Template Engine**: Blade Templates (SSR)
- **Styling**: Tailwind CSS v4 (CSS-first architecture)
- **Reactivity**: Alpine.js
- **Bundler**: Vite 8.x
- **Internationalization**: Dual language support for Vietnamese (`vi`) and English (`en`)

### Infrastructure & Services
- **Containerization**: Docker & Docker Compose (PHP-FPM, Nginx, PostgreSQL, Redis)
- **Payment Gateways**:
  - **SePay**: Automated bank transfer matching via VietQR Webhook (3–5s confirmation)
  - **Stripe**: International credit card processing (Visa, MasterCard, JCB)
  - **CoLearn Wallet**: Internal wallet balance for instant 1-Click course checkout
- **Storage**: AWS S3 / Local Storage
- **Email**: Mailgun / SMTP

---

## Core Features

### 1. Student Portal
- **Course Discovery**: Search, filter by category and difficulty, sort by price and popularity.
- **Course Details**: Curriculum outline with sections/lessons, free preview lessons, instructor bio.
- **Cart & Discounts**: Add/remove cart items, apply promotional discount coupons.
- **CoLearn Wallet (`/wallet`)**: Top up balance via SePay QR, track transaction history and balance changes.
- **Flexible Checkout (`/checkout`)**:
  - Instant 1-Click purchase using internal wallet balance.
  - Dynamic SePay QR code with real-time payment detection.
  - International credit card payment via Stripe.
- **Learning Player (`/learn/{slug}/{lesson}`)**:
  - Video player (YouTube, S3, MP4), attachments, and Markdown lesson notes.
  - Lesson completion toggle with automatic course progress calculation.
- **Order History**: Track order status and view digital invoices/receipts.

### 2. Teacher Portal (`/teacher`)
- **Dashboard**: Overview of total students, courses, estimated earnings, and recent enrollments.
- **Course Management (`/teacher/courses`)**:
  - Create and update courses with formatted currency inputs (`<x-money-input>`).
  - Define Learning Outcomes.
  - Manage Sections and Lessons (Video, Text, Duration, Free Preview toggle).
  - Submit courses for admin approval (`Draft` ➔ `Pending Review`).
- **Student Management (`/teacher/students`)**: View enrolled students by course, track progress and enrollment dates.
- **Analytics & Reports (`/teacher/analytics`)**: 12-month revenue chart, enrollment trends, and individual course performance.
- **Instructor Profile (`/teacher/profile`)**: Manage headline, bio, social links, avatar, and password.

### 3. Admin Panel (`/admin`)
- **Dashboard**: System-wide revenue metrics, student growth, total orders, and pending course reviews.
- **Course Review (`/admin/courses`)**: Review full course content, approve (`Published`) or reject (`Rejected` with feedback).
- **User Management (`/admin/users`)**: Manage roles, ban/unban user accounts, adjust wallet balances with audit logs.
- **Orders & Refunds (`/admin/orders`)**: View orders, issue automated refunds credited directly back to the student's wallet.
- **Category Management (`/admin/categories`)**: Create, update, and reorder course categories.
- **Coupon Management (`/admin/coupons`)**: Create percentage-based or fixed-amount discount codes with usage limits and expiration dates.
- **Transaction Logs (`/admin/transactions`)**: Audit all financial transactions (deposits, purchases, refunds).
- **System Settings (`/admin/settings`)**: Configure SePay credentials, Stripe keys, SMTP mail, OAuth, and AWS S3 directly from the UI.

---

## Demo Accounts

The database seed provides the following pre-configured accounts:

| Role | Email | Password | URL Path |
| :--- | :--- | :--- | :--- |
| **Admin** | `admin@colearn.test` | `password` | `/admin` |
| **Teacher** | `teacher@colearn.test` | `password` | `/teacher` |
| **Student** | `student@colearn.test` | `password` | `/login` |

---

## Prerequisites

- **Docker & Docker Compose** (Linux, macOS, Windows)
- **Node.js** >= 20.x & **npm**
- **Composer**

---

## Installation

```bash
# 1. Clone the repository & prepare environment file
git clone <repository-url> CoLearn && cd CoLearn
copy .env.example .env     # Linux / macOS: cp .env.example .env

# 2. Start all Docker containers
docker compose up -d

# 3. Initialize application (App Key, Storage Link, Migrate, Seed)
composer docker:init

# 4. Install dependencies & start development server
npm install
npm run dev
```

> **Technical Notes**:
> - `npm run dev` starts the Vite dev server with Hot Module Replacement (HMR) and real-time live preview. For production deployment, use `npm run build`.
> - `composer docker:init` only needs to be run once upon initial setup. Database data is persisted permanently in the `postgres_data` Docker volume.
> - To execute Artisan commands inside Docker: `docker exec colearn-app php artisan <command>`.

---

## Useful Commands

| Command | Description |
| :--- | :--- |
| `npm run dev` | Start Vite development server with Hot Module Replacement (HMR) |
| `npm run build` | Compile frontend assets for production |
| `composer docker:init` | Initialize App Key, Storage Link, Migrate Fresh, and Seed data in Docker |
| `composer docker:seed` | Re-seed the database inside Docker |
| `composer docker:test` | Run the complete automated test suite inside Docker |
| `composer test` | Run local PHPUnit test suite |
| `composer lint` | Check code styling with Laravel Pint |
| `composer format` | Auto-format code (Pint + Prettier Blade) |
| `docker compose up -d` | Start all Docker services in background |
| `docker compose down` | Stop all Docker containers |

---

## Directory Structure

```
CoLearn/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Course approvals, users, orders, settings
│   │   │   ├── Auth/           # Login, register, password reset, Socialite OAuth
│   │   │   ├── Student/        # Cart, checkout, wallet, learning, SePay webhook
│   │   │   ├── Teacher/        # Dashboard, courses, lessons, students, analytics
│   │   │   ├── CourseCatalogController.php
│   │   │   └── CourseDetailController.php
│   │   └── Middleware/
│   ├── Models/                 # User, Course, Section, Lesson, Order, Transaction...
│   ├── Notifications/          # Password reset and system emails
│   └── Services/
│       ├── CartService.php     # Cart & coupon calculation logic
│       ├── OrderService.php    # Wallet payment, order fulfillment & enrollment
│       ├── SePayService.php    # SePay QR generation, webhook validation & reconciliation
│       ├── SettingService.php  # Dynamic system settings stored in database
│       └── StripeService.php   # Stripe payment gateway integration
├── database/
│   ├── factories/              # Model factories (User, Course, Category)
│   ├── migrations/             # Database schema migrations (UUID, ULID, Indexes)
│   └── seeders/                # RoleAndPermissionSeeder, CourseSeeder
├── docker/
│   ├── nginx/default.conf      # Nginx reverse proxy configuration
│   └── php/Dockerfile          # PHP 8.3 FPM Dockerfile
├── lang/
│   ├── en/messages.php         # English localization strings
│   └── vi/messages.php         # Vietnamese localization strings
├── resources/
│   ├── css/app.css             # Tailwind CSS v4 entry
│   ├── js/app.js               # Alpine.js entry
│   └── views/
│       ├── admin/              # Admin panel views
│       ├── auth/               # Authentication views
│       ├── checkout/           # Checkout & payment views
│       ├── components/         # Blade components: <x-sepay-modal>, <x-money-input>...
│       ├── courses/            # Course catalog & detail views
│       ├── layouts/            # Base layouts
│       ├── learn/              # Video learning player view
│       ├── teacher/            # Teacher portal views
│       └── wallet/             # CoLearn wallet & top-up views
├── routes/
│   ├── web.php                 # Web routes (Session auth + RBAC middleware)
│   └── api.php                 # API routes (Sanctum token)
├── tests/
│   ├── Feature/
│   │   ├── SePayPaymentTest.php    # SePay QR & Webhook fulfillment tests
│   │   └── TeacherPortalTest.php   # Teacher portal RBAC & CRUD tests
│   └── Unit/
├── docker-compose.yml          # Docker Compose configuration
└── composer.json
```

---

## Services & Payment Configuration

### 1. SePay Configuration (Automated Bank QR)
Configure in `.env` or in Admin Panel (`/admin/settings ➔ SePay`):
```env
SEPAY_API_KEY=YOUR_SEPAY_API_KEY
SEPAY_BANK_ID=MBBank               # Bank identifier (MBBank, VCB, ACB, Techcombank, NCB...)
SEPAY_ACCOUNT_NO=0123456789        # Bank account number
SEPAY_ACCOUNT_NAME="COLEARN PLATFORM"
```
- **Webhook Endpoint**: Set in your SePay Dashboard to:
  ```
  https://your-domain.com/payment/sepay/webhook
  ```

### 2. Social Login (Google & Facebook)
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

### 3. Email (SMTP)
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

## License
This project is open-sourced software licensed under the [MIT License](LICENSE).
