# CLAUDE.md

This file provides guidance to AI agents when working with code in this repository.

## Stack

**Laravel 13** (PHP 8.3+) backend with **Vite + Tailwind CSS 4** frontend. Blade templates + Alpine.js for all views. PHPUnit/Pest for testing. **PostgreSQL** database with Eloquent ORM. Laravel Sanctum for auth (SPA cookie-based for web, token-based for future mobile API). Redis for caching/queues (via Docker). Docker for full dev environment.

## Project Mode: blade-ssr

Frontend is Blade + Tailwind CSS 4 + Alpine.js. Controllers return `view()`. No React/Vue SPA.

## Project: CoLearn

**Nền tảng bán khóa học trực tuyến** — mô hình đơn vị đào tạo (kiểu TITV/28Tech), không phải marketplace.

- **Student**: đăng ký, mua khóa học, xem video, làm bài tập/kiểm tra
- **Teacher/Lecturer**: tạo khóa học, upload video/tài liệu, giao bài tập/kiểm tra
- **Admin**: quản lý hệ thống, duyệt khóa học, quản lý users

Reference sites: Udemy (UI/UX), TITV (mô hình VN), 28Tech (mô hình VN)

## Commands

```bash
# Full dev environment (Laravel server + queue worker + logs + Vite)
composer dev

# Individual
php artisan serve
npm run dev
php artisan queue:listen

# First-time setup
composer setup           # installs deps, generates key, migrates, links storage, seeds

# Tests
composer test            # all tests
php artisan test --filter=TestClassName  # single test

# Linting & formatting
composer lint            # php pint --test (check only)
composer format          # php pint + prettier --write (auto-fix)

# Docker
docker compose up -d     # start all services
docker compose down      # stop all services
```

## Architecture

### Core Domain: Online Course Platform

#### Models & Relationships

| Model | Key Relations | Description |
|-------|--------------|-------------|
| User | hasMany Courses (as teacher), belongsToMany Courses (as student via enrollments), hasMany Orders, hasMany Reviews | Unified user with roles |
| Course | belongsTo User (teacher), belongsTo Category, hasMany Sections, hasMany Enrollments, hasMany Reviews | Khóa học |
| Category | hasMany Courses | Danh mục khóa học |
| Section | belongsTo Course, hasMany Lessons | Chương/phần |
| Lesson | belongsTo Section, hasMany LessonCompletions | Bài giảng (video, text, tài liệu) |
| Order | belongsTo User, belongsToMany Courses (via order_items) | Đơn hàng |
| Review | belongsTo User, belongsTo Course | Đánh giá |
| Coupon | belongsToMany Courses | Mã giảm giá |
| Enrollment | belongsTo User, belongsTo Course | Ghi danh khóa học |

*Note: Entity list sẽ mở rộng theo nhu cầu (Quiz, Assignment, Certificate, etc.)*

#### Status Workflows

- **Course**: `draft` → `pending_review` → `published` → `archived`
- **Order**: `pending` → `paid` → `refunded`
- **Enrollment**: `active` → `completed` → `expired`

### Request Flow

```
routes/web.php → Http/Controllers/ → Models → resources/views/
routes/api.php → Http/Controllers/Api/ → Models → JSON response
```

Two controller groups:
- **Web** (`app/Http/Controllers/`): Sanctum SPA cookie auth, Blade views
- **API** (`app/Http/Controllers/Api/`): Sanctum token auth, JSON responses (cho mobile app sau)

### Authentication

- **Web (primary)**: Sanctum SPA cookie-based auth — session + CSRF
- **API (future mobile)**: Sanctum token-based auth — Bearer token
- **OAuth**: Google + Facebook via Laravel Socialite
- **RBAC**: `spatie/laravel-permission` — roles (student, teacher, admin) + granular permissions
- **Rate limiting**: Laravel defaults (dễ nâng cấp sau)

### Third-Party Integrations

| Service | Provider | Package/SDK |
|---------|----------|-------------|
| Payment (VN) | VNPay | `vnpay` SDK |
| Payment (International) | Stripe | `laravel/cashier` |
| Email | Mailgun | Laravel Mail (built-in driver) |
| Cloud Storage | AWS S3 | Laravel Flysystem (built-in driver) |
| OAuth | Google, Facebook | `laravel/socialite` |
| Permissions | RBAC | `spatie/laravel-permission` |

### Infrastructure

- **Docker**: PostgreSQL, Redis, Laravel app, Nginx
- **Queue**: Redis driver — email, video processing, certificate generation
- **Cache**: Redis driver
- **File Storage**: AWS S3 (video, tài liệu, thumbnails)

### Models & Conventions

- Use `casts()` method (not `protected $casts` array) for newer convention
- Explicit return types on every relationship: `BelongsTo`, `HasMany`, etc.
- Import from `Illuminate\Database\Eloquent\Relations\*`
- Always use `HasFactory` + create/update the matching factory when adding a new model
- Custom query logic belongs in scopes (`scopeXxx`) on the model, not raw `DB::` queries
- Prefer `Model::query()` over `DB::table()`

### Asset Pipeline

Vite entry: `resources/css/app.css` (Tailwind) + `resources/js/app.js` (Alpine.js). `@vite` directive in Blade layouts.

## Code Quality

- **PHP formatting**: Laravel Pint (run `composer format`)
- **Blade formatting**: Prettier with blade plugin
- **Git hooks**: Husky pre-commit (lint-staged) + commit-msg (commitlint)
- **Commit format**: Conventional Commits (`feat:`, `fix:`, `docs:`, `chore:`, etc.)

## Git Workflow (CRITICAL)

- **NEVER commit directly to `main`** — always create a feature branch first
- Branch naming: `<prefix>/<short-description>` (e.g. `feature/user-auth`, `fix/login-redirect`)
- Commit on the feature branch only, then merge via PR
- See `docs/convention-git.md` for full branching and commit conventions

## Key Files

- `routes/web.php` — Web routes with Sanctum SPA auth
- `routes/api.php` — API routes with Sanctum token guard
- `app/Models/User.php` — User model with HasApiTokens + roles
- `composer.json` — All dev/build/test/lint/format scripts
- `bootstrap/app.php` — Route registration, middleware, exception handling
- `docker-compose.yml` — Docker services configuration

## Code Consistency (CRITICAL)

1. **Only touch files related to the current task** — no drive-by refactors
2. **Follow existing patterns** — don't introduce new patterns unless explicitly asked
3. **Minimal diffs** — smallest change that achieves the goal
4. **Never modify existing migrations** — always create new ones
5. **Preserve all comments and docblocks** — even Vietnamese ones

See `.agent/skills/code-consistency/SKILL.md` for full rules.

## Conventions

### Eloquent
- Use `casts()` method (not `protected $casts` array) for newer convention
- Explicit return types on every relationship: `BelongsTo`, `HasMany`, etc.
- Import from `Illuminate\Database\Eloquent\Relations\*`
- Always use `HasFactory` + create/update the matching factory when adding a new model
- Custom query logic belongs in scopes (`scopeXxx`) on the model, not raw `DB::` queries
- Prefer `Model::query()` over `DB::table()`

### Language & i18n
- **Code** (variables, classes, methods, database columns): always English
- **Comments**: minimal — only when logic is non-obvious, avoid redundant comments
- **UI strings**: i18n via Laravel localization (`lang/vi/`, `lang/en/`) — 2 languages: Tiếng Việt + English
- **Data** (user names, course titles, lesson content, filenames): must support Unicode (Vietnamese + English)
- **Database**: PostgreSQL with UTF-8 encoding — all text columns support Vietnamese characters
- Never hardcode UI strings — always use `__('key')` or `@lang('key')` in Blade
