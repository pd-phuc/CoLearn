# Git Convention

> Quy ước Git cho dự án. AI agents và developers đều phải tuân thủ.

---

## 1. Branching Convention

### Công thức đặt tên

```
<prefix>/<short-description>
```

- Chữ thường, phân cách bằng dấu gạch ngang (`-`)
- Mô tả ngắn gọn, rõ ràng component bị ảnh hưởng

### Danh mục tiền tố

#### Phát triển
| Prefix | Mục đích | Ví dụ |
|--------|----------|-------|
| `feature/` | Tính năng mới | `feature/user-authentication` |
| `bugfix/` | Sửa lỗi trên develop | `bugfix/fix-login-redirect` |
| `hotfix/` | Sửa lỗi khẩn cấp trên main | `hotfix/critical-api-crash` |
| `refactor/` | Tái cấu trúc (không đổi logic) | `refactor/clean-order-queries` |

#### Hạ tầng
| Prefix | Mục đích | Ví dụ |
|--------|----------|-------|
| `deps/` | Cài/nâng cấp thư viện lớn | `deps/upgrade-laravel-13` |
| `chore/` | Config, task vụn vặt | `chore/update-tailwind-config` |
| `ci/` | CI/CD, Docker, deploy | `ci/setup-github-actions` |

#### Tài liệu & Test
| Prefix | Mục đích | Ví dụ |
|--------|----------|-------|
| `docs/` | Cập nhật tài liệu | `docs/update-api-docs` |
| `test/` | Viết tests | `test/order-checkout-flow` |

#### Giao diện
| Prefix | Mục đích | Ví dụ |
|--------|----------|-------|
| `style/` | Chỉ CSS/Tailwind | `style/fix-footer-spacing` |
| `ui/` | Component giao diện | `ui/redesign-dashboard` |

---

## 2. Commit Message Convention (Conventional Commits)

### Công thức

```
<type>(<scope>): <description>
```

- **type**: Loại thay đổi (bắt buộc)
- **scope**: Phạm vi ảnh hưởng (tùy chọn)
- **description**: Mô tả ngắn, viết thường, không dấu chấm cuối

### Danh mục type

| Type | Mục đích | Ví dụ |
|------|----------|-------|
| `feat` | Tính năng mới | `feat(auth): add google oauth login` |
| `fix` | Sửa lỗi | `fix(cart): correct price calculation` |
| `refactor` | Tái cấu trúc code | `refactor(order): extract service layer` |
| `docs` | Tài liệu | `docs: update api documentation` |
| `style` | Format code (không đổi logic) | `style: fix blade indentation` |
| `test` | Thêm/sửa tests | `test(auth): add login feature tests` |
| `chore` | Config, deps, task vụn vặt | `chore: update composer dependencies` |
| `ci` | CI/CD pipeline | `ci: add github actions workflow` |
| `perf` | Tối ưu performance | `perf(query): add index to orders table` |
| `revert` | Revert commit trước | `revert: revert "feat(auth): add oauth"` |

### Quy tắc

1. **Một commit = một thay đổi logic** — không gộp nhiều thay đổi
2. **Description viết tiếng Anh**, bắt đầu bằng động từ: `add`, `fix`, `update`, `remove`
3. **Không dấu chấm** ở cuối description
4. **Dòng đầu tối đa 70 ký tự** — tính cả `type`, `scope`, dấu hai chấm và khoảng trắng
   - Không có giới hạn tối thiểu: `docs: add README` (16 ký tự) là hợp lệ
   - Cần giải thích thêm thì xuống dòng trống rồi viết body, body không bị giới hạn
   - Enforce bởi commitlint (`header-max-length: 70`) — commit quá dài sẽ bị chặn
5. **Breaking changes**: Thêm `!` sau type — `feat!: change auth flow`

### Ví dụ tốt vs xấu

```bash
# ✅ Tốt
feat(product): add image upload endpoint
fix(auth): handle expired token gracefully
refactor(order): move validation to form request
docs: add deployment guide

# ❌ Xấu
update code                    # Quá chung chung
fix bug                        # Bug gì? Ở đâu?
feat: add login, register, forgot password  # Gộp nhiều thay đổi
FEAT: Add User Model           # Viết hoa
```

---

## 3. Git Workflow

### Quy trình

```
main ← (protected, chỉ merge qua PR)
  └── develop ← (nhánh phát triển chính)
        ├── feature/user-auth
        ├── bugfix/fix-login
        └── refactor/clean-queries
```

1. **Checkout từ develop** (hoặc main nếu không có develop):
   ```bash
   git checkout develop
   git pull origin develop
   git checkout -b feature/your-task-name
   ```

2. **Code và commit** trên feature branch

3. **Push và tạo Pull Request** để review trước khi merge

4. **Xóa branch** sau khi merge thành công

### Quy tắc vàng

- **KHÔNG** commit trực tiếp vào `main` — luôn tạo branch mới
- **KHÔNG** đặt tên nhánh theo cá nhân (`phuc/fix-code`)
- **KHÔNG** gộp nhiều task vào một nhánh
- **KHÔNG** dùng tên chung chung (`fix-bug`, `update`)

---

## 4. Enforced by Tools

| Tool | Chức năng |
|------|----------|
| **Commitlint** | Kiểm tra commit message theo Conventional Commits |
| **Husky** | Git hooks — chạy lint trước commit |
| **Lint-staged** | Chỉ lint files đã thay đổi |

---

## 5. Issue-Driven Workflow

> Áp dụng từ 2026-08-27. Công việc theo dõi qua GitHub Issues tại `pd-phuc/CoLearn`.

### Nguyên tắc

Mỗi thay đổi bắt nguồn từ **một issue**. Chưa có issue thì tạo trước khi viết code.

### Đặt tên nhánh kèm số issue

```
<prefix>/<số-issue>-<mô-tả-ngắn>
```

| Ví dụ | Issue |
|-------|-------|
| `fix/35-go-to-learning-link` | #35 |
| `feat/22-transaction-filters` | #22 |
| `refactor/30-form-requests-checkout` | #30 |

Số issue trong tên nhánh giúp truy ngược ngữ cảnh khi đọc lại git log sau nhiều tháng.

### Liên kết PR với issue

Trong phần mô tả PR, thêm dòng:

```
Closes #35
```

GitHub sẽ tự đóng issue khi PR được merge. Dùng `Closes` / `Fixes` / `Resolves` đều được.

Nếu PR chỉ xử lý một phần, dùng `Refs #35` để liên kết mà không đóng issue.

### Viết issue

Issue **không** dùng format Conventional Commits — `fix(scope):` chỉ dành cho commit message.

| | |
|---|---|
| ❌ | `fix(auth): tính năng ban user không hoạt động` |
| ✅ | `Banned users can still log in — banned_at is written but never checked` |

**Title**: tiếng Anh, mô tả hành vi quan sát được, code identifier bọc backtick.

**Body** — bug report:

```markdown
### Summary
### Steps to reproduce
### Expected behaviour
### Actual behaviour
### Evidence          (file:line làm bằng chứng)
### Impact
### Suggested fix
### Acceptance criteria   (checklist)
```

**Body** — enhancement / tech-debt:

```markdown
### Summary
### Current behaviour
### Why it matters
### Proposed solution
### Acceptance criteria
```

### Label

Mỗi issue gắn tối thiểu 3 nhóm label:

| Nhóm | Giá trị |
|------|---------|
| Loại | `bug` · `security` · `enhancement` · `tech-debt` |
| Mức độ | `severity:critical` · `severity:high` · `severity:medium` · `severity:low` |
| Vùng | `area:payment` · `area:auth` · `area:admin` · `area:teacher` · `area:course` · `area:wallet` · `area:cart` |

`severity:critical` dành cho: thất thoát tiền, bypass xác thực/phân quyền, sai lệch dữ liệu.

### Lệnh thường dùng

```bash
gh issue list --label severity:critical
gh issue view 35
gh pr create --fill --base main
```
