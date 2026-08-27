# CLAUDE.md

This file provides guidance to AI agents when working with code in this repository.

## Stack

**Laravel 13** (PHP 8.3+) backend with **Vite + Tailwind CSS 4** frontend. Blade templates + Alpine.js for all views. PHPUnit for testing (Pest is *not* installed). **PostgreSQL** database with Eloquent ORM. Laravel Sanctum for auth (SPA cookie-based for web, token-based for future mobile API). Redis for caching/queues (via Docker). Docker for full dev environment.

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

Bảng dưới phản ánh đúng các model **đang tồn tại** trong `app/Models/`.

| Model | Key Relations | Description |
|-------|--------------|-------------|
| User | hasMany Courses (as teacher), hasMany Enrollments, hasMany Orders, hasMany Transactions, hasMany LessonCompletions | Unified user with roles. PK = UUID |
| Course | belongsTo User (teacher), belongsTo Category, hasMany Sections, hasMany Enrollments | Khóa học. PK = ULID |
| Category | hasMany Courses | Danh mục khóa học |
| Section | belongsTo Course, hasMany Lessons | Chương/phần |
| Lesson | belongsTo Section, hasMany LessonCompletions | Bài giảng (video, text, tài liệu) |
| LessonCompletion | belongsTo User, belongsTo Lesson | Đánh dấu đã học xong 1 bài |
| Enrollment | belongsTo User, belongsTo Course | Ghi danh khóa học |
| Order | belongsTo User, belongsTo Coupon, hasMany OrderItems, hasManyThrough Courses | Đơn hàng (`order_type`: `course` \| `topup`) |
| OrderItem | belongsTo Order, belongsTo Course | Dòng trong đơn hàng |
| Coupon | hasMany Orders | Mã giảm giá |
| Transaction | belongsTo User, belongsTo Order | Sổ cái ví — mọi biến động số dư |
| Setting | — | Cấu hình runtime lưu DB (mail, OAuth, SePay, S3) |

**PK convention**: `User` dùng UUID (36 ký tự), tất cả model còn lại dùng ULID (26 ký tự). Khi thêm cột FK trỏ tới user phải dùng `uuid()`, trỏ tới model khác dùng `ulid()`.

*Chưa tồn tại (dự kiến mở rộng): Review, Quiz, Assignment, Certificate.*

#### Status Workflows

- **Course**: `draft` → `pending_review` → `published` → `archived`
  - `reject` đưa về `draft` kèm `rejection_reason`
- **Order**: `pending` → `paid` → `refunded`, hoặc `pending` → `cancelled`
- **Enrollment**: `active` → `completed` → `expired`
- **Transaction**: bất biến — chỉ ghi thêm, không sửa/xóa

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
| Payment (VN) | SePay (VietQR Auto-Bank Webhook) | Custom `SePayService` |
| Payment (International) | Stripe | `stripe/stripe-php` — ⚠️ **chưa cài** trong `composer.json`; `StripeService` sẽ throw nếu bật |
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

Vite entry: `resources/css/app.css` (Tailwind) + `resources/js/app.js`. `@vite` directive in Blade layouts.

⚠️ Thực tế hiện tại: `resources/js/app.js` đang **rỗng**. Alpine.js được nạp từ CDN trong `resources/views/layouts/app.blade.php` chứ không qua npm/Vite. Khi cần thêm JS, cân nhắc chuyển Alpine về npm trước để có versioning và không phụ thuộc mạng ngoài.

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

### Issue-driven (từ 2026-08-27)

Công việc được theo dõi qua GitHub Issues tại `pd-phuc/CoLearn`.

- Mỗi thay đổi bắt nguồn từ 1 issue. Không có issue thì tạo trước khi code.
- Tạo nhánh bằng `gh issue develop <số> --base main --checkout --name <prefix>/<mô-tả>` — GitHub gắn nhánh vào issue, issue tự đóng khi PR merge
- **Tên nhánh không mang số issue** (GitHub đã lưu liên kết, nhánh lại bị xóa sau merge). Phần mô tả chỉ 2–3 từ. Ví dụ: `fix/dead-links`
- Truy vết trong git: commit chính mang footer `Refs: #<số>` — liên kết GitHub không tồn tại trong git history
- PR body vẫn thêm `Closes #<số>` cho tường minh
- Label: `severity:*` (mức độ) + `area:*` (vùng ảnh hưởng) + loại (`bug` / `security` / `enhancement` / `tech-debt`)
- Issue title viết tiếng Anh, mô tả hành vi quan sát được — **không** dùng format Conventional Commits (`fix(scope):` chỉ dành cho commit message)

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

### Controllers & Validation
- **Validate bằng Form Request**, không dùng `$request->validate()` inline trong controller
  - Đặt tại `app/Http/Requests/<Domain>/<Action>Request.php`
  - Quyền truy cập đặt trong `authorize()`, không rải `abort(403)` trong controller
  - *Nợ kỹ thuật hiện tại*: 29 chỗ còn validate inline, đang được xử lý dần — code mới phải dùng Form Request
- Controller mỏng: điều phối request/response, logic nghiệp vụ đẩy xuống `app/Services/`
- Kiểm tra quyền trên model dùng Policy (`$this->authorize()`), không copy-paste `if ($x->user_id !== auth()->id())`

### Views (Blade)
- **Không truy vấn DB trong Blade** — dữ liệu do controller hoặc View Composer cung cấp
  - *Nợ kỹ thuật hiện tại*: `layouts/app.blade.php`, `partials/footer.blade.php`, `welcome.blade.php` còn gọi `Model::where()` trực tiếp
- Không để `href="#"` cho link đã có route thật
- Mọi chuỗi hiển thị đi qua `__()`

### Tiền & Ví (CRITICAL)
- Mọi thay đổi số dư phải nằm trong `DB::transaction()` + `lockForUpdate()` trên row user
- Ghi `Transaction` cho **mọi** biến động số dư — không có ngoại lệ
- Ràng buộc nghiệp vụ (đủ số dư, số tiền > 0, hạn thanh toán) phải enforce ở backend, không chỉ hiển thị ở UI
- Giá trị fallback khi thiếu cấu hình phải **fail-closed** ở nhánh xác thực

### Language & i18n
- **Code** (variables, classes, methods, database columns): always English
- **Comments**: minimal — only when logic is non-obvious, avoid redundant comments
- **UI strings**: i18n via Laravel localization (`lang/vi/`, `lang/en/`) — 2 languages: Tiếng Việt + English
- **Data** (user names, course titles, lesson content, filenames): must support Unicode (Vietnamese + English)
- **Database**: PostgreSQL with UTF-8 encoding — all text columns support Vietnamese characters
- Never hardcode UI strings — always use `__('key')` or `@lang('key')` in Blade
