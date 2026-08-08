# 🎨 CoLearn — Official Design System & Graphic Specification

> **Design Directive**: Graphic aesthetics strictly follow `fcode-web-system-challenge-3` (floating glassmorphic headers, OKLCH primary colors, `rounded-2xl` cards, floating keyframe animations). Layout structure combines **Udemy** (categories mega-menu, course cards, ratings, search), **28Tech** (learning paths), and **TITV.vn** (clear Vietnamese learning progression).

---

## 1. Graphic Aesthetic Principles (`fcode` Style)

### 1.1. Floating Glassmorphism (`.floating-header`)
- **Header**: Floating rounded container offset from screen top:
  `sticky top-3 z-50 mx-auto max-w-7xl rounded-2xl border border-slate-200/80 bg-white/95 backdrop-blur-xl shadow-md transition-all`
- **Glow Effects**: Hover glow blur behind logo and badge icons:
  `absolute inset-0 rounded-xl bg-orange-500/30 blur-md opacity-0 group-hover:opacity-100 transition-opacity`

### 1.2. Card Aesthetics (`.card-fcode`)
- **Card Container**: `rounded-2xl border border-slate-200/80 bg-white p-6 shadow-xs hover:shadow-2xl hover:-translate-y-1 transition-all duration-300`
- **Soft Backgrounds**: App background soft gradient `bg-gradient-to-b from-slate-100/60 via-slate-50 to-white`.

### 1.3. Keyframe Animations
```css
@keyframes floating {
    0% { transform: translateY(0px) rotate(-6deg); }
    50% { transform: translateY(-12px) rotate(2deg); }
    100% { transform: translateY(0px) rotate(-6deg); }
}
.animate-floating { animation: floating 4s ease-in-out infinite; }
```

---

## 2. Palette Tokens & Color Hierarchy

| Token Name | Hex / Class | Purpose |
|------------|-------------|---------|
| `color-primary` | `#f97316` (`orange-500`) | Primary brand identity, buttons, active highlights |
| `color-primary-hover` | `#ea580c` (`orange-600`) | Hover state for primary actions |
| `color-primary-tint` | `#fff7ed` (`orange-50`) | Card hover tints, category icon wrappers |
| `color-secondary-accent` | `#10b981` (`emerald-500`) | Free price tags, success badges, completion checks |
| `color-surface` | `slate-100/60` to `slate-50` | Background canvas |
| `color-card` | `#ffffff` (`white`) | Glassmorphic cards, popovers, dropdowns |
| `color-text-main` | `#0f172a` (`slate-900`) | Headings, primary text |
| `color-text-sub` | `#64748b` (`slate-500`) | Subtitles, lesson count, metadata |

---

## 3. Hybrid Layout Structure Guidelines

### 3.1. Navigation & Header (Udemy + TITV + fcode)
- **Brand**: Logo with glowing blur circle backdrop.
- **Categories Dropdown (Udemy)**: Mega-menu with course counts per category.
- **Search Bar (Udemy)**: Input with keyboard shortcut hint (`Ctrl+K`).
- **Learning Paths (28Tech/TITV)**: Links for "Trang chủ", "Lộ trình học", "Khóa học".
- **Language Switcher**: Compact pill `🇻🇳 VI | 🇬🇧 EN`.
- **User Avatar (fcode)**: Rounded avatar with role indicator badge (Admin/Teacher/Student).

### 3.2. Course Cards (Udemy + 28Tech Hybrid)
- **Thumbnail**: Aspect ratio `16:9` with category badge overlay.
- **Level Tag**: `beginner`, `intermediate`, `advanced` pill badges.
- **Rating Stars (Udemy)**: 5.0 ★ rating with review counts.
- **Stats**: Total lessons count (`📖 X bài học`), duration in hours/mins.
- **Price Tag (Udemy)**: Discount price in bold Orange + original price struck-through.
- **Instructor Avatar**: Teacher name and avatar.

### 3.3. Learning Paths Section (28Tech Style)
- 4 Visual Path Cards:
  1. *Web Fullstack Laravel 13*
  2. *C++ & Thuật Toán*
  3. *Cơ Sở Dữ Liệu PostgreSQL*
  4. *DevOps & Cloud AWS*

---

## 4. UI Standards for Future Features

### 4.1. Course Detail Page (`/courses/{slug}`)
- **Hero Header**: Dark glassmorphic banner with course title, rating, instructor info, enrolled count, and video preview sticky card on the right.
- **Curriculum Accordion (Udemy Style)**: Sections list with expandable lesson items, preview indicators, and duration badges.

### 4.2. Video Player & Learning Interface (`/courses/{slug}/learn`)
- **Left/Center**: Responsive video player with custom controls and lesson notes.
- **Right Sidebar (Udemy/TITV Style)**: Interactive lesson list with completion checkboxes (`LessonCompletion`), section headers, and progress bar (`X% completed`).

### 4.3. Teacher Dashboard (`/teacher/courses`)
- **Stat Cards (`card-fcode`)**: Total courses, total students, total revenue.
- **Course Table**: Published/Draft status badges, quick edit CTA, section/lesson builder.

### 4.4. Admin Panel (`/admin/dashboard`)
- **Glassmorphic Cards**: User management, course approval workflow (`pending_review` -> `published`), revenue reports.
