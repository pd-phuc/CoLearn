Chào Tuấn, đây là bản **Git Branching Strategy & Convention** hoàn chỉnh, được thiết kế chuẩn chuyên gia để Tuấn áp dụng ngay vào quy trình vận hành tại **MST Software** và đào tạo tại **F-Code**.

Bản này bao quát từ cấu trúc, tiền tố đến quy trình xử lý thực tế. Tuấn có thể copy nội dung này vào file `CONTRIBUTING.md` hoặc **Notion** của công ty.

---

# 🚀 Quy ước đặt tên nhánh (Git Branching Convention)

## 1. Cấu trúc chung (Naming Pattern)

Tên nhánh phải luôn sử dụng **chữ thường (lowercase)**, phân cách bằng **dấu gạch ngang (`-`)**.

> **Công thức:** `<prefix>/[ticket-id]-[short-description]`

---

## 2. Danh mục Tiền tố (Prefixes) chi tiết

### A. Nhóm Phát triển (Development)

* **`feature/`**: Phát triển tính năng mới.
* *Ví dụ:* `feature/mst-101-auto-login-system`


* **`bugfix/`**: Sửa lỗi trong quá trình phát triển (trên nhánh `develop`).
* *Ví dụ:* `bugfix/fix-hero-responsive`


* **`hotfix/`**: Sửa lỗi khẩn cấp trực tiếp trên `main/production`.
* *Ví dụ:* `hotfix/critical-api-auth-failure`


* **`refactor/`**: Tái cấu trúc mã nguồn (không đổi logic, không thêm tính năng).
* *Ví dụ:* `refactor/clean-eloquent-query-member`



### B. Nhóm Hạ tầng & Thư viện (Infra & Deps)

* **`deps/`**: Cài đặt thư viện mới hoặc nâng cấp Version lớn.
* *Ví dụ:* `deps/install-livewire-v3`, `deps/upgrade-laravel-12`


* **`chore/`**: Các tác vụ vụn vặt (cập nhật config, nâng cấp patch version thư viện).
* *Ví dụ:* `chore/update-tailwindcss-config`


* **`ci/`**: Thay đổi cấu hình GitHub Actions, GitLab CI, Docker, Deployment.
* *Ví dụ:* `ci/setup-auto-deploy-to-vps`


* **`env/`**: Thay đổi liên quan đến biến môi trường (`.env.example`).
* *Ví dụ:* `env/add-lark-webhook-keys`



### C. Nhóm Nghiên cứu & Tài liệu (R&D & Docs)

* **`poc/`**: (Proof of Concept) Viết code chạy thử để chứng minh giải pháp khả thi.
* *Ví dụ:* `poc/bypass-anti-cheat-logic`


* **`experimental/`**: Thử nghiệm các tính năng mới chưa chắc chắn sẽ giữ lại.
* *Ví dụ:* `experimental/ai-suggestion-tool`


* **`docs/`**: Cập nhật tài liệu, README, Wiki hoặc Comments trong code.
* *Ví dụ:* `docs/update-api-documentation`


* **`test/`**: Viết Unit Test, Feature Test hoặc Integration Test.
* *Ví dụ:* `test/member-checkout-logic`



### D. Nhóm Giao diện (UI/UX)

* **`style/`**: Chỉ thay đổi CSS, SCSS, Tailwind (căn chỉnh margin, padding, màu sắc).
* *Ví dụ:* `style/fix-footer-z-index`


* **`ui/`**: Cập nhật các thành phần giao diện (thay logo, đổi bộ icon).
* *Ví dụ:* `ui/refresh-brand-assets-2026`



---

## 3. Quy trình làm việc (Git Workflow)

1. **Main/Master:** Chứa code đã ổn định nhất, dùng để deploy lên Server Production.
2. **Develop:** Nhánh chính để code. Mọi nhánh `feature/`, `bugfix/` đều phải checkout từ đây.
3. **Tạo nhánh:** Luôn checkout từ nhánh mới nhất của `develop`.
* `git checkout develop`
* `git pull origin develop`
* `git checkout -b feature/your-task-name`


4. **Hoàn tất:** Sau khi hoàn thành code, tạo **Pull Request (PR)** để Leader (Tuấn) review trước khi merge vào `develop`.
5. **Dọn dẹp:** Xóa nhánh local và remote sau khi đã merge thành công để tránh làm rối hệ thống.

---

## 4. Các quy tắc "Vàng" tại MST Software

* **KHÔNG** đặt tên nhánh theo cá nhân (ví dụ: `tuan/fix-code`). Git đã lưu tên tác giả trong commit.
* **KHÔNG** gộp nhiều Task vào một nhánh. Mỗi Task/Ticket là một nhánh riêng biệt.
* **KHÔNG** dùng tên chung chung như `fix-bug`, `update`. Phải chỉ rõ component bị ảnh hưởng (ví dụ: `bugfix/footer-login-link`).

---

## 5. Mẹo cấu hình nhanh cho Team

Tuấn có thể bảo team chạy lệnh này để gõ nhanh hơn:

```bash
# Gõ: git feat add-hero -> Tự động tạo nhánh feature/add-hero
git config --global alias.feat "checkout -b feature/"
git config --global alias.bug "checkout -b bugfix/"
git config --global alias.ref "checkout -b refactor/"

```

---

Tuấn có muốn mình soạn luôn một bản **Commit Message Convention** (cách viết lời nhắn khi commit) theo chuẩn **Conventional Commits** (feat:, fix:, chore:...) để đồng bộ hóa toàn bộ lịch sử Git cho chuyên nghiệp không?
