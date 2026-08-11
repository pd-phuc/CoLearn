# Feature: Admin Panel

## Status: In Progress (Phase 1 — Core Infrastructure Complete)

## Description

Bảng điều khiển quản trị cho admin — quản lý users, courses, orders, categories, coupons, payment settings. Dashboard tổng quan với thống kê.

## Architecture

| Component | Detail |
|-----------|--------|
| Layout | Dark sidebar (`bg-slate-900`) + light content area |
| Auth | `role:admin` middleware (spatie/laravel-permission) |
| Namespace | `App\Http\Controllers\Admin\` |
| Views | `resources/views/admin/` |
| Settings storage | `settings` table (key-value, encrypted secrets) + Redis cache |

## Modules

### 1. Dashboard (`/admin`) ✅
- KPI cards: Total revenue, monthly revenue, total users, new users (30d), courses published, courses pending review, total orders, total enrollments
- Recent activity panels: orders, users, pending courses
- **TODO:** Chart.js revenue/enrollment charts

### 2. User Management (`/admin/users`) ✅
- List/search/filter by role
- View detail (profile + orders + enrollments + transactions)
- Edit profile (name, email)
- Ban/Unban (toggle `banned_at`)
- Adjust balance (admin deposit/withdraw with reason)

### 3. Course Approval (`/admin/courses`) ✅
- List all courses with status/search filter
- View course detail (content, sections/lessons)
- Approve → `published` (sets `reviewed_at`, `reviewed_by`)
- Reject → `draft` + rejection reason
- **TODO:** Email notification on approve/reject

### 4. Order & Transaction Management ✅
- `GET /admin/orders` — List all orders with status filter
- `GET /admin/orders/{order}` — Order detail with items
- `POST /admin/orders/{order}/refund` — Refund (credit wallet, update status)
- `GET /admin/transactions` — Transaction log viewer

### 5. Category Management (`/admin/categories`) ✅
- CRUD with course count
- Sort order field
- Delete protection (cannot delete with courses)

### 6. Coupon Management (`/admin/coupons`) ✅
- CRUD with usage stats
- Toggle active/inactive
- Discount type: percent / fixed amount
- Date range (starts_at, expires_at)

### 7. Settings (`/admin/settings`) ✅
- **SePay config**: bank_id, account_no, account_name, api_key (encrypted)
- **Stripe config**: secret (encrypted), publishable key, webhook_secret (encrypted)
- **Platform config**: name, email, currency, min_topup

## Database Changes

| Migration | Changes |
|-----------|---------|
| `create_settings_table` | `settings` table (group, key, value, is_encrypted) |
| `add_admin_panel_columns` | `users.banned_at`, `categories.sort_order`, `courses.rejection_reason/reviewed_at/reviewed_by/is_featured` |

## Routes (31 total)

```
GET    /admin                           → Dashboard
GET    /admin/users                     → User list
GET    /admin/users/{user}              → User detail
PUT    /admin/users/{user}              → Update user
POST   /admin/users/{user}/toggle-ban   → Ban/unban
POST   /admin/users/{user}/adjust-balance → Balance adjustment
GET    /admin/users/{user}/edit         → Edit user form
GET    /admin/courses                   → Course list
GET    /admin/courses/{course}          → Course detail
POST   /admin/courses/{course}/approve  → Approve course
POST   /admin/courses/{course}/reject   → Reject course
GET    /admin/orders                    → Order list
GET    /admin/orders/{order}            → Order detail
POST   /admin/orders/{order}/refund     → Refund order
GET    /admin/transactions              → Transaction log
CRUD   /admin/categories                → Category CRUD (resource)
CRUD   /admin/coupons                   → Coupon CRUD (resource)
GET    /admin/settings                  → Settings page
PUT    /admin/settings                  → Update settings
```

## Services

- `SettingService` — `get(group, key, default)`, `set(group, key, value, encrypted?)`, `getGroup(group)`, `updateGroup()`
- `SePayService` — reads from `SettingService` → fallback `config/services.php` → `.env`
- `StripeService` — same pattern as SePayService

## Models

- `Setting` (ULID) — group, key, value, is_encrypted. Auto-decrypts on read.

## Permissions (RoleAndPermissionSeeder)
- `manage-settings`
- `manage-transactions`
