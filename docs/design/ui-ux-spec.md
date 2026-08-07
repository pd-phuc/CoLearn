# 🎨 CoLearn — UI/UX & Design System Specification

## 1. Design Tokens & Color Palette

Inspired by **28Tech & TITV** (Clean, energetic, bright educational platform) with **Vibrant Orange** as the primary identity color.

### Palette Tokens
| Token Name | Hex Code | Tailwind v4 Equivalent | Purpose |
|------------|----------|------------------------|---------|
| `color-primary-50` | `#fff7ed` | `orange-50` | Light backgrounds, active item tints |
| `color-primary-500` | `#f97316` | `orange-500` | Primary buttons, active tabs, highlights |
| `color-primary-600` | `#ea580c` | `orange-600` | Primary hover states, prominent headings |
| `color-primary-700` | `#c2410c` | `orange-700` | Active click states, contrast text |
| `color-secondary-500` | `#10b981` | `emerald-500` | Badges, success alerts, price discounts |
| `color-surface-bg` | `#f8fafc` | `slate-50` | App background |
| `color-card-bg` | `#ffffff` | `white` | Card components, navbar, modals |
| `color-text-main` | `#0f172a` | `slate-900` | Primary typography |
| `color-text-sub` | `#475569` | `slate-600` | Subtitles, labels, secondary info |

---

## 2. Component Micro-Interactions

### Buttons
- **Primary Action (`.btn-primary`)**: Orange background (`#f97316`), white text, `rounded-xl`, `font-semibold`.
  - *Hover state*: `bg-orange-600 translate-y-[-1px] shadow-lg shadow-orange-500/25` (smooth 200ms transition).
  - *Active state*: `translate-y-[0px] shadow-sm`.
- **OAuth Icon Pill (`.btn-oauth-icon`)**: White background, `border border-slate-200`, `rounded-xl`, centered SVG icon.
  - *Hover state*: `border-slate-300 bg-slate-50 shadow-md scale-[1.03]` (smooth 200ms transition).
  - *Active state*: `scale-[0.98]`.

### Navbar & Dropdowns
- **Category Dropdown**: Activated on click/hover with Alpine.js (`x-data`, `x-show`, `x-transition`).
  - Dropdown enters with `fade & slide down (duration 150ms)`.
- **Language Switcher**: Toggle pill (🇻🇳 VN | 🇬🇧 EN) updating locale session via `/lang/{locale}` route.

---

## 3. Screen Specifications & Wireframe Mappings

### 3.1. Base Layout (`layouts/app.blade.php`)
```
┌─────────────────────────────────────────────────────────────────────────┐
│ [CoLearn Logo]  [Categories ▾]  [Search Course...]    [VN|EN] [Login] [Register] │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│                             MAIN CONTENT BLOCK                          │
│                                                                         │
├─────────────────────────────────────────────────────────────────────────┤
│ Footer: Platform Info | Top Categories | Contact | Copyright © 2026     │
└─────────────────────────────────────────────────────────────────────────┘
```

### 3.2. Homepage (`welcome.blade.php`)
- **Hero Section**: Bright gradient card with Orange CTA button ("Khám phá khóa học ngay"), illustration, and key metrics (Học viên, Khóa học, Giảng viên).
- **Featured Categories**: 4 Category cards with icons and course counts. Hovering scales card up slightly.
- **Top Courses Grid**: Responsive grid (`grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4`).
  - Course Card: Thumbnail image with overlay badge, category name, title, teacher info, star rating, and price (with original price strike-through).

### 3.3. Login Screen (`auth/login.blade.php`)
- Centered split layout or card container (`max-w-md mx-auto`).
- **Header**: CoLearn logo, "Welcome back / Chào mừng bạn quay trở lại".
- **Social Login Row**: Horizontal flex row with 2 white pill buttons:
  - **Google button**: Centered Google G multi-color SVG icon (`w-6 h-6`).
  - **Facebook button**: Centered Facebook blue f SVG icon (`w-6 h-6`).
- **Divider**: "HOẶC ĐĂNG NHẬP BẰNG EMAIL" / "OR LOGIN WITH EMAIL".
- **Form Fields**:
  - Email input with icon.
  - Password input with toggle show/hide eye icon (Alpine.js).
  - Checkbox "Ghi nhớ đăng nhập" & Link "Quên mật khẩu?".
  - Full-width Orange Submit Button ("Đăng nhập").
- **Footer Link**: "Chưa có tài khoản? Đăng ký ngay".

### 3.4. Register Screen (`auth/register.blade.php`)
- Similar card layout with Name, Email, Password, Password Confirmation fields.
- Social registration row (Google/Facebook icons).
- Terms & Privacy policy checkbox.

---

## 4. Internationalization (i18n) Keys Structure

- `lang/vi/auth.php` & `lang/en/auth.php`: Login, register, email, password, remember me, forgot password, social login strings.
- `lang/vi/messages.php` & `lang/en/messages.php`: Navbar items, categories, search placeholder, course levels, price format.
