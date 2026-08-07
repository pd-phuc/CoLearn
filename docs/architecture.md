# Architecture Overview

## Request Lifecycle

```
Browser/Client
    ↓
public/index.php
    ↓
bootstrap/app.php (Application bootstrap)
    ↓
Middleware Pipeline (CSRF, Auth, etc.)
    ↓
Router (routes/web.php | routes/api.php)
    ↓
Controller
    ↓
Model (Eloquent ORM) ↔ Database
    ↓
Response (Blade View | JSON)
```

## Authentication Architecture

### Web (Session-based)
```
Browser → POST /login → LoginController → Auth::attempt()
    → Session regeneration → Redirect to dashboard
```

### API (Token-based, Sanctum)
```
Client → POST /api/login → Api\AuthController → Auth::attempt()
    → createToken() → Return JSON { user, token }

Client → GET /api/user (Authorization: Bearer <token>)
    → auth:sanctum middleware → Return user JSON
```

## Folder Conventions

### Controllers
- `app/Http/Controllers/` — Public web controllers
- `app/Http/Controllers/Auth/` — Session auth controllers
- `app/Http/Controllers/Api/` — API controllers (Sanctum)
- `app/Http/Controllers/Admin/` — Admin panel controllers (create per project)

### Models
- One model per database table
- Use `HasFactory` trait + matching factory in `database/factories/`
- Business logic in model methods and scopes
- Relationships with explicit return types

### Views
- `resources/views/layouts/` — Layout templates (app, admin, guest)
- `resources/views/components/` — Reusable Blade components
- `resources/views/auth/` — Auth pages (login, register)
- `resources/views/{feature}/` — Feature-specific pages

## Database Conventions

- Migration file names: `YYYY_MM_DD_HHMMSS_description.php`
- Table names: plural, snake_case (`users`, `blog_posts`)
- Foreign keys: `{model}_id` (`user_id`, `post_id`)
- Pivot tables: alphabetical singular (`post_tag`, not `tag_post`)
- Soft deletes: Add `deleted_at` column when needed
- Timestamps: Always include `created_at`, `updated_at`

## Environment Configuration

All sensitive config lives in `.env` (never committed). See `.env.example` for the full list of available variables.

Key environment variables:
- `DB_*` — Database connection
- `SANCTUM_STATEFUL_DOMAINS` — SPA domains for cookie-based Sanctum auth
- `MAIL_*` — Email configuration
- `QUEUE_CONNECTION` — Queue driver (sync, database, redis)
