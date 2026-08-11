# CoLearn — Design System & UI Standards

> **Source of truth**: The codebase itself. This document describes the design patterns **actually in use**, not aspirational specs.

---

## 1. Design Principles

### 1.1. Clean, Functional Aesthetic
- **Light theme**: `bg-slate-50`, white cards, `text-slate-900` primary text
- **Cards**: `rounded-2xl border border-slate-200/80 bg-white shadow-xs` — hover: `hover:shadow-2xl hover:-translate-y-1`
- **Spacing**: Generous padding (`p-5`, `p-6`), consistent gap (`gap-4`, `gap-6`)
- **Typography**: Plus Jakarta Sans (Google Fonts), font weights: 400/500/600/700/800/900

### 1.2. Color Palette

| Token | Tailwind Class | Usage |
|-------|---------------|-------|
| Primary | `orange-500` / `orange-600` | Buttons, active states, brand accent |
| Primary tint | `orange-50` / `orange-100` | Hover backgrounds, badge backgrounds |
| Success | `emerald-500` / `emerald-600` | Completed states, positive values |
| Warning | `amber-500` / `amber-600` | Pending states |
| Error | `rose-500` / `rose-600` | Error states, banned, delete |
| Surface | `slate-50` → `white` | Page background gradient |
| Text primary | `slate-900` | Headings, body |
| Text secondary | `slate-400` / `slate-500` | Metadata, labels, timestamps |
| Info | `blue-500` / `blue-600` | Info badges, links |

### 1.3. Component Patterns

**Status Badges**: Short labels, no compound phrases.
```html
<span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase bg-emerald-100 text-emerald-700">Completed</span>
```

**Buttons (Primary)**: Uses `.btn-primary` utility from Tailwind.
```html
<button class="btn-primary px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider shadow-md cursor-pointer">Action</button>
```

**Form Inputs**: Consistent styling across all forms.
```html
<input class="w-full px-3 py-2.5 border border-slate-200 rounded-xl text-sm font-bold text-slate-900 focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500">
```

---

## 2. Layout Structure

### 2.1. Public/Student Layout (`layouts/app.blade.php`)
- **Header**: Sticky floating glassmorphic bar (`top-3 max-w-7xl rounded-2xl bg-white/95 backdrop-blur-xl`)
- **Logo**: Orange gradient square + "CoLearn" text
- **Navigation**: Categories dropdown, search, language switcher, auth buttons/avatar
- **Content**: `max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`
- **Footer**: Multi-column footer

### 2.2. Admin Layout (`admin/layouts/admin.blade.php`)
- **Sidebar**: Fixed dark (`bg-slate-900`), 64w, collapsible on mobile
- **Sidebar nav**: Icon + label, active state = `bg-orange-600 text-white`
- **Topbar**: Sticky white, page title, breadcrumb link back to main site
- **Content area**: Full-width with padding

### 2.3. Learning Player (`learn/show.blade.php`)
- **Distraction-free**: No standard header/footer
- **Left/center**: Video player or lesson content
- **Right sidebar**: Curriculum with completion checkboxes, collapsible via ☰

---

## 3. Iconography

- **100% SVG inline icons** — no emoji, no icon fonts
- Source: Heroicons (outline style, `stroke-width="1.5"` or `"2"`)
- Size: `w-4 h-4` for inline, `w-5 h-5` for nav/buttons, `w-9 h-9` → `w-11 h-11` for featured icons

---

## 4. i18n

- All UI strings use `__('messages.key')` or `@lang('key')`
- Two languages: Vietnamese (default) + English
- Lang files: `lang/vi/messages.php`, `lang/en/messages.php`
- Code, variables, database columns: always English

---

## 5. Responsive Breakpoints

- Mobile-first approach
- `sm:` (640px), `md:` (768px), `lg:` (1024px), `xl:` (1280px)
- Admin sidebar hidden on mobile, shown via hamburger toggle
- Course cards: 1 col → 2 col → 3/4 col grid
