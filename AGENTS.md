# AGENTS.md — Laravel Template

> Project-level rules for AI agents working in this repository.
> Backend is ALWAYS Laravel. Frontend depends on Project Mode.

## Stack

- **Backend**: Laravel 13, PHP 8.3+, Eloquent ORM, MySQL
- **Frontend**: Vite + Tailwind CSS 4, Blade templates (or React SPA)
- **Auth**: Laravel Sanctum (API tokens) + Session (web)
- **Testing**: PHPUnit / Pest
- **Code Style**: Laravel Pint, Prettier (Blade), EditorConfig

## Project Mode

This template supports two frontend modes. Check `CLAUDE.md` for `## Project Mode:`.

| Mode | Frontend | Backend Response |
|------|----------|-----------------|
| `blade-ssr` | Blade + Tailwind + Alpine.js | `return view()` |
| `react-spa` | React (Vite) + Tailwind | `return response()->json()` |

If mode is not set, **run the onboarding skill** first:
→ Read `.agent/skills/project-onboarding/SKILL.md`

## Commands

| Task | Command |
|------|---------|
| Dev server | `composer dev` |
| Setup | `composer setup` |
| Test | `composer test` |
| Lint (check) | `composer lint` |
| Format (fix) | `composer format` |

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
- API controllers return JSON responses (react-spa mode or API routes)
- Admin routes use `EnsureIsAdmin` middleware

### File Structure
```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/           # Session-based web auth
│   │   ├── Api/            # Sanctum token-based API auth
│   │   └── Admin/          # Admin panel controllers
│   └── Middleware/
├── Models/                  # Eloquent models
└── Providers/

routes/
├── web.php                  # Web routes (session auth)
├── api.php                  # API routes (Sanctum guard)
└── console.php              # Artisan commands

resources/
├── views/                   # Blade templates
├── css/app.css              # Tailwind entry
└── js/app.js                # JS entry
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
7. **No business logic in template**: Keep this repo framework-agnostic
