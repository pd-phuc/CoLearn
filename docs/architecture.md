# CoLearn — Architecture Documentation

## Overview

CoLearn là nền tảng bán khóa học trực tuyến, mô hình đơn vị đào tạo (tham khảo TITV, 28Tech).
Không phải marketplace — chỉ teacher/admin tạo khóa học, student mua và học.

## System Architecture

```
┌─────────────────────────────────────────────────────┐
│                    Docker Environment                │
│                                                      │
│  ┌──────────┐  ┌──────────┐  ┌───────┐  ┌────────┐ │
│  │  Nginx   │  │ Laravel  │  │ Redis │  │Postgres│ │
│  │  (proxy) │→ │ PHP-FPM  │→ │(cache │  │  (DB)  │ │
│  │  :80/443 │  │  :9000   │  │ queue)│  │ :5432  │ │
│  └──────────┘  └──────────┘  └───────┘  └────────┘ │
│                      │                               │
│                      ▼                               │
│              ┌──────────────┐                        │
│              │  Vite (dev)  │                        │
│              │   :5173      │                        │
│              └──────────────┘                        │
└─────────────────────────────────────────────────────┘
                       │
            ┌──────────┼──────────┐
            ▼          ▼          ▼
        ┌───────┐  ┌───────┐  ┌───────┐
        │ AWS   │  │Mailgun│  │ VNPay │
        │  S3   │  │(email)│  │Stripe │
        │(files)│  │       │  │(pay)  │
        └───────┘  └───────┘  └───────┘
```

## Technology Stack

### Backend
| Component | Technology | Rationale |
|-----------|-----------|-----------|
| Framework | Laravel 13 | PHP ecosystem, Blade SSR, mature |
| PHP | 8.3+ | Typed properties, enums, fibers |
| ORM | Eloquent | Laravel native, active record |
| Database | PostgreSQL | JSONB support, better concurrency, scalability |
| Cache | Redis | Fast, pub/sub, queue driver |
| Queue | Redis driver | Email, video processing, certificates |

### Frontend
| Component | Technology | Rationale |
|-----------|-----------|-----------|
| Templates | Blade | Laravel native SSR |
| CSS | Tailwind CSS 4 | Utility-first, CSS-first config |
| JS | Alpine.js | Lightweight reactivity for Blade |
| Build | Vite | Fast HMR, native ESM |

### Auth & Security
| Component | Technology | Rationale |
|-----------|-----------|-----------|
| Web Auth | Sanctum SPA (cookie) | CSRF + session, secure for SSR |
| API Auth | Sanctum (token) | Future mobile app |
| OAuth | Socialite | Google, Facebook login |
| RBAC | spatie/laravel-permission | Roles + granular permissions |
| Rate Limit | Laravel default | Upgradeable later |

### Third-Party Services
| Service | Provider | Laravel Integration |
|---------|----------|-------------------|
| Payment (VN) | VNPay | VNPay SDK |
| Payment (Intl) | Stripe | laravel/cashier |
| Email | Mailgun | Mail driver (built-in) |
| Storage | AWS S3 | Flysystem driver (built-in) |

### Infrastructure
| Component | Technology |
|-----------|-----------|
| Containerization | Docker + Docker Compose |
| Web Server | Nginx (reverse proxy) |
| PHP Runtime | PHP-FPM |
| CI/CD | TBD |

## Application Layers

```
┌─────────────────────────────────────────┐
│              Routes (web.php, api.php)  │
├─────────────────────────────────────────┤
│           Middleware (auth, roles)       │
├─────────────────────────────────────────┤
│         Controllers (thin, delegating)  │
├─────────────────────────────────────────┤
│    Form Requests (validation + authz)   │
├─────────────────────────────────────────┤
│      Services (business logic layer)    │
├─────────────────────────────────────────┤
│     Models (Eloquent, scopes, casts)    │
├─────────────────────────────────────────┤
│   Notifications / Events / Jobs         │
├─────────────────────────────────────────┤
│       Database (PostgreSQL + Redis)     │
└─────────────────────────────────────────┘
```

## Data Model (ERD Overview)

```
User (roles: student, teacher, admin)
 ├── hasMany Course (as teacher)
 ├── belongsToMany Course (as student, via enrollments)
 ├── hasMany Order
 ├── hasMany Review
 └── hasMany LessonCompletion

Course
 ├── belongsTo User (teacher)
 ├── belongsTo Category
 ├── hasMany Section
 ├── hasMany Enrollment
 ├── hasMany Review
 └── belongsToMany Coupon

Category
 └── hasMany Course

Section
 ├── belongsTo Course
 └── hasMany Lesson

Lesson
 ├── belongsTo Section
 └── hasMany LessonCompletion

Order
 ├── belongsTo User
 └── hasMany OrderItem → belongsTo Course

Enrollment
 ├── belongsTo User
 └── belongsTo Course

Review
 ├── belongsTo User
 └── belongsTo Course

Coupon
 └── belongsToMany Course
```

## User Roles & Permissions

### Student
- Browse/search courses
- Purchase courses (VNPay/Stripe)
- Access enrolled courses (video, materials)
- Complete lessons, track progress
- Write reviews
- View order history

### Teacher/Lecturer
- All student permissions
- Create/edit own courses
- Upload video/materials to S3
- Create sections & lessons
- Assign homework/quizzes (future)
- View course analytics (enrollments, revenue)

### Admin
- All teacher permissions
- Manage all users (CRUD, ban)
- Approve/reject courses (pending_review → published)
- Manage categories
- Manage coupons
- View platform analytics
- Manage payments/orders

## Status Workflows

### Course Lifecycle
```
draft → pending_review → published → archived
  │         │                │
  └─────────┘                └── (admin or teacher can archive)
  (teacher submits)    (admin approves)
```

### Order Lifecycle
```
pending → paid → refunded
  │         │
  └─────────┘
  (payment callback)
```

### Enrollment Lifecycle
```
active → completed → expired
  │         │
  └─────────┘
  (all lessons completed)
```

## Queue & Background Jobs

| Job | Trigger | Priority |
|-----|---------|----------|
| SendWelcomeEmail | User registration | High |
| SendOrderConfirmation | Order paid | High |
| SendCourseCompletionEmail | All lessons completed | Medium |
| ProcessVideoUpload | Teacher uploads video | Medium |
| GenerateThumbnail | Video processed | Low |

## File Storage Structure (S3)

```
colearn/
├── courses/{course_id}/
│   ├── thumbnail.jpg
│   ├── sections/{section_id}/
│   │   └── lessons/{lesson_id}/
│   │       ├── video.mp4
│   │       └── materials/
│   │           ├── slides.pdf
│   │           └── code.zip
├── avatars/{user_id}/
│   └── avatar.jpg
└── temp/
    └── uploads/
```

## Security Considerations

1. **RBAC enforcement**: Every route protected by role/permission middleware
2. **Course access**: Students can only access enrolled courses
3. **Data scraping prevention**: Rate limiting + auth checks on all content routes
4. **CSRF protection**: All web forms use CSRF tokens
5. **SQL injection**: Eloquent ORM parameterized queries
6. **XSS prevention**: Blade `{{ }}` auto-escaping
7. **File upload validation**: Mime type + size checks before S3 upload
8. **Payment webhook verification**: VNPay/Stripe signature validation

## Development Workflow

1. `docker compose up -d` — Start PostgreSQL, Redis, Nginx
2. `composer setup` — Install deps, migrate, seed
3. `composer dev` — Start Laravel + Vite + Queue worker
4. Code → Test → Format → Commit
5. `composer test` — Run test suite
6. `composer format` — Auto-fix code style
