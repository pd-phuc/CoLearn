# AGENTS.md — CoLearn

> Project-level rules for AI agents working in this repository.
> Backend is ALWAYS Laravel. Frontend is Blade + Tailwind + Alpine.js (blade-ssr mode).

## Project: CoLearn

Nền tảng bán khóa học trực tuyến — mô hình đơn vị đào tạo (kiểu TITV/28Tech).
3 roles: Student (học), Teacher (tạo khóa học), Admin (quản lý).

## Project Mode: blade-ssr

| Component | Technology |
|-----------|----------|
| Backend | Laravel 13, PHP 8.3+, Eloquent ORM, PostgreSQL |
| Frontend | Blade + Tailwind CSS 4 + Alpine.js |
| Auth | Sanctum (SPA cookie + token), Socialite (Google/Facebook) |
| RBAC | spatie/laravel-permission |
| Cache/Queue | Redis (Docker) |
| Infra | Docker (PostgreSQL, Redis, Nginx, Laravel) |
| Payment | VNPay + Stripe |
| Email | Mailgun |
| Storage | AWS S3 |

## Stack

- **Backend**: Laravel 13, PHP 8.3+, Eloquent ORM, PostgreSQL
- **Frontend**: Vite + Tailwind CSS 4, Blade templates, Alpine.js
- **Auth**: Laravel Sanctum (SPA cookie for web, token for API) + Socialite (Google, Facebook)
- **RBAC**: spatie/laravel-permission (roles + permissions)
- **Testing**: PHPUnit / Pest
- **Code Style**: Laravel Pint, Prettier (Blade), EditorConfig
- **Infra**: Docker (PostgreSQL, Redis, Nginx)

## Domain Models

### Core Entities
- **User** — Unified user with roles (student, teacher, admin)
- **Course** — Khóa học, thuộc về 1 teacher
- **Category** — Danh mục khóa học
- **Section** — Chương/phần trong khóa học
- **Lesson** — Bài giảng (video, text, tài liệu)
- **Order** — Đơn hàng mua khóa học
- **Enrollment** — Ghi danh vào khóa học
- **Review** — Đánh giá khóa học
- **Coupon** — Mã giảm giá

### Future Entities (expandable)
- Quiz, Assignment, Certificate, Notification, Wishlist, LearningPath

## Business Rules

1. **Student** chỉ có thể tham gia khóa học đã mua (enrolled)
2. **Teacher** tạo khóa học → trạng thái `draft` → admin duyệt → `published`
3. **Admin** duyệt khóa học, quản lý users, quản lý thanh toán
4. Mỗi khóa học thuộc **1 category**, có nhiều **sections**, mỗi section có nhiều **lessons**
5. Thanh toán qua **VNPay** (VN) hoặc **Stripe** (quốc tế)
6. Video/tài liệu lưu trên **AWS S3**
7. Email thông báo xử lý qua **queue** (Redis)

## Commands

| Task | Command |
|------|---------|
| Dev server | `composer dev` |
| Setup | `composer setup` |
| Test | `composer test` |
| Lint (check) | `composer lint` |
| Format (fix) | `composer format` |
| Docker up | `docker compose up -d` |
| Docker down | `docker compose down` |

## Code Consistency (CRITICAL)

**ALL agents MUST follow these rules:**

1. **Only touch files related to the current task** — no drive-by refactors
2. **Follow existing patterns** — don't introduce new patterns unless asked
3. **Minimal diffs** — smallest change that achieves the goal
4. **Never modify existing migrations** — always create new ones
5. **Preserve all comments and docblocks** — even Vietnamese ones

→ Full rules: `.agent/skills/code-consistency/SKILL.md`

## Conventions

### Code Style
- Follow Laravel Pint conventions (`pint.json` preset)
- Blade templates formatted by Prettier
- Commits follow **Conventional Commits** format (enforced by commitlint)

### Eloquent
- Use `casts()` method, not `$casts` property
- Explicit return types on relationships (`BelongsTo`, `HasMany`, etc.)
- Always include `HasFactory` trait + corresponding factory
- Use scopes for custom queries, avoid raw `DB::` calls

### Controllers
- Web controllers return Blade views (blade-ssr mode)
- API controllers return JSON responses (for future mobile app)
- Admin routes use role-based middleware via spatie/laravel-permission

### File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Session-based web auth + Socialite
│   │   ├── Api/            # Sanctum token-based API auth (future mobile)
│   │   ├── Admin/          # Admin panel controllers
│   │   ├── Teacher/        # Teacher dashboard controllers
│   │   └── Student/        # Student-facing controllers
│   └── Middleware/
├── Models/                  # Eloquent models
├── Services/                # Business logic services
├── Notifications/           # Email notifications
└── Providers/

routes/
├── web.php                  # Web routes (Sanctum SPA cookie auth)
├── api.php                  # API routes (Sanctum token guard)
└── console.php              # Artisan commands

resources/
├── views/
│   ├── layouts/             # Base layouts
│   ├── components/          # Blade components
│   ├── courses/             # Course views
│   ├── admin/               # Admin panel views
│   ├── teacher/             # Teacher dashboard views
│   └── auth/                # Auth views (login, register, OAuth)
├── css/app.css              # Tailwind entry
└── js/app.js                # Alpine.js entry

docker/
├── nginx/                   # Nginx config
├── php/                     # PHP-FPM Dockerfile
└── ...
```

## Agent Skills

### Laravel-Specific
| Skill | Purpose |
|-------|---------|
| `project-onboarding` | Interview user → generate project docs |
| `laravel-conventions` | Controller, route, Eloquent patterns |
| `laravel-migration-workflow` | Safe schema changes, factories, seeders |
| `code-consistency` | Prevent unnecessary code modifications |
| `laravel-eloquent-conventions` | Model best practices |

### General
- Frontend, backend, database, testing, security, deployment
- Code review, debugging, performance, SEO
- See `.agent/ARCHITECTURE.md` for the full skill catalog

## Rules

1. **Read before write**: Always understand existing code patterns before making changes
2. **Detect project mode**: Read `CLAUDE.md` → Project Mode before any code
3. **Code consistency**: Follow the 5 rules above — non-negotiable
4. **Test your changes**: Run `composer test` after any logic change
5. **Format your code**: Run `composer format` before committing
6. **Small commits**: One logical change per commit, conventional format
7. **i18n**: UI strings via `__('key')` / `@lang('key')` — support Tiếng Việt + English. Code in English. Minimal comments (only when non-obvious).
8. **NEVER commit to `main`**: Always create a feature branch (`feature/`, `fix/`, `refactor/`, etc.) and commit there. See `docs/convention-git.md`


