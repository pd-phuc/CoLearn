# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Stack

**Laravel 13** (PHP 8.3+) backend with **Vite + Tailwind CSS 4** frontend. Blade templates for all views. PHPUnit for testing. MySQL database with Eloquent ORM. Laravel Sanctum for API token authentication. Session-based auth for web routes.

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
```

## Architecture

### Request Flow

```
routes/web.php → Http/Controllers/ → Models → resources/views/
routes/api.php → Http/Controllers/Api/ → Models → JSON response
```

Two controller groups:
- **Web** (`app/Http/Controllers/`): Session-based auth, Blade views
- **API** (`app/Http/Controllers/Api/`): Sanctum token auth, JSON responses

### Authentication

Dual-mode auth is scaffolded:
- **Session-based** (web routes): `Auth\LoginController` — standard login/logout with session
- **Token-based** (API routes): `Api\AuthController` — Sanctum token register/login/logout
- **Admin middleware**: `EnsureIsAdmin` — role-based access control example

### Models & Conventions

- Use `casts()` method (not `protected $casts` array) for newer convention
- Explicit return types on every relationship: `BelongsTo`, `HasMany`, etc.
- Import from `Illuminate\Database\Eloquent\Relations\*`
- Always use `HasFactory` + create/update the matching factory when adding a new model
- Custom query logic belongs in scopes (`scopeXxx`) on the model, not raw `DB::` queries
- Prefer `Model::query()` over `DB::table()`

### Asset Pipeline

Vite entry: `resources/css/app.css` (Tailwind) + `resources/js/app.js`. `@vite` directive in Blade layouts.

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

- `routes/web.php` — Web routes with session auth
- `routes/api.php` — API routes with Sanctum guard
- `app/Models/User.php` — User model with HasApiTokens + role
- `composer.json` — All dev/build/test/lint/format scripts
- `bootstrap/app.php` — Route registration, middleware, exception handling

## Code Consistency (CRITICAL)

1. **Only touch files related to the current task** — no drive-by refactors
2. **Follow existing patterns** — don't introduce new patterns unless explicitly asked
3. **Minimal diffs** — smallest change that achieves the goal
4. **Never modify existing migrations** — always create new ones
5. **Preserve all comments and docblocks** — even Vietnamese ones

See `.agent/skills/code-consistency/SKILL.md` for full rules.

## Project Onboarding

When starting a new project from this template, run the onboarding flow:
- Read `.agent/skills/project-onboarding/SKILL.md`
- Interview the user about domain, models, auth strategy, project mode
- Update this file (CLAUDE.md) with project-specific information

