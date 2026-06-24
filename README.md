# Hệ thống Quản lý Thông tin Hộ dân cư Xã (Bản nâng cao)

Hệ thống quản lý hành chính cấp xã toàn diện, phân quyền chi tiết (RBAC), theo dõi vết hoạt động (Audit Log), và tích hợp các phân hệ nghiệp vụ cốt lõi: Hộ tịch & Cư trú, Kinh tế & Lao động, An sinh xã hội & Y tế & Giáo dục, Nghĩa vụ & An ninh quốc phòng, Đất đai & Hạ tầng.

---

## 📌 Các Phân hệ Nghiệp vụ & Module Chức năng

Hệ thống được chia thành các phân hệ nghiệp vụ độc lập nhưng liên kết chặt chẽ qua cơ sở dữ liệu:

### 1. Hộ tịch & Cư trú (Core Cư dân)
- **Quản lý Sổ hộ khẩu:** Đăng ký hộ mới, lưu trữ mã hộ, số sổ, thông tin chủ hộ, địa chỉ và số thành viên.
- **Biến động hộ:** Logic nghiệp vụ tách hộ (chuyển một số thành viên sang sổ mới), nhập hộ (thêm thành viên mới), chuyển khẩu đi/đến ngoài địa bàn xã.
- **Quản lý Nhân khẩu:** Lưu trữ chi tiết thông tin công dân (Họ tên, CCCD/Mã định danh, ngày sinh, dân tộc, tôn giáo, học vấn, tình trạng hôn nhân...).
- **Thay đổi nhân khẩu:** Đăng ký Tạm trú, Tạm vắng (có thời hạn tự động cập nhật trạng thái), khai tử (khóa nhân khẩu) và cập nhật thông tin cá nhân.
- **Quan hệ nhân khẩu:** Định nghĩa rõ ràng vai trò với chủ hộ (vợ, chồng, con, cháu, anh/chị/em...).

### 2. Kinh tế, Lao động & Việc làm
- **Trạng thái lao động:** Theo dõi chi tiết tình trạng việc làm (Có việc làm, thất nghiệp, học sinh/sinh viên, nghỉ hưu, mất sức lao động).
- **Hồ sơ nghề nghiệp:** Phân loại loại hình công việc (Nhà nước, tư nhân, tự do, nước ngoài) và ngành nghề (Nông nghiệp, công nghiệp, dịch vụ).
- **Lao động đi xa & Xuất khẩu:** Quản lý danh sách người dân làm việc ngoài tỉnh hoặc xuất khẩu lao động (nước làm việc, thời hạn hợp đồng).
- **Doanh nghiệp & Hộ kinh doanh:** Danh mục các cơ sở sản xuất, kinh doanh trên địa bàn xã để phục vụ kết nối việc làm.
- **Kết nối việc làm:** Hỗ trợ điều phối, giới thiệu người lao động thất nghiệp với nhu cầu tuyển dụng của doanh nghiệp địa phương.

### 3. An sinh xã hội, Y tế & Giáo dục
- **Diện chính sách:** Quản lý danh sách thương binh, bệnh binh, thân nhân liệt sĩ, người có công với cách mạng.
- **Bảo trợ xã hội:** Quản lý hộ nghèo, hộ cận nghèo, người khuyết tật, người già neo đơn, trẻ em mồ côi.
- **Quỹ từ thiện & Gói trợ cấp:** Tạo các chiến dịch cấp phát quà/tiền trợ cấp (Trợ cấp khó khăn, quà Tết, quà 27/7...), tự động quét danh sách đối tượng đủ điều kiện theo quy định và ghi nhận trạng thái đã nhận.
- **Y tế & Giáo dục:** Theo dõi tỷ lệ tiêm chủng của trẻ em, bảo hiểm y tế (BHYT) tự nguyện/bắt buộc, danh sách học sinh bỏ học/hiếu học.

### 4. Nghĩa vụ & An ninh quốc phòng
- **Nghĩa vụ quân sự (NVQS):** Tự động lọc danh sách nam thanh niên trong độ tuổi gọi nhập ngũ (18-25 tuổi, hoặc đến 27 tuổi đối với người có trình độ Đại học/Cao đẳng). Theo dõi trạng thái khám tuyển: Đủ điều kiện, tạm hoãn (lý do cụ thể), trúng tuyển, đã nhập ngũ, xuất ngũ.
- **Dân quân tự vệ:** Quản lý danh sách lực lượng dân quân tự vệ nòng cốt của xã, quá trình huấn luyện và lịch trực ban.
- **An ninh trật tự:** Theo dõi các đối tượng thuộc diện quản lý đặc biệt và danh sách xử phạt vi phạm hành chính tại địa phương.

### 5. Đất đai, Hạ tầng & Tài sản hộ dân
- **Quản lý Đất đai:** Lưu trữ thông tin diện tích đất thổ cư, đất nông nghiệp do hộ gia đình sở hữu (Số tờ, số thửa, loại đất, diện tích, tình trạng sử dụng).
- **Thuế & Phí địa phương:** Theo dõi và ghi nhận các khoản nộp ngân sách của hộ dân (Thuế đất phi nông nghiệp, phí vệ sinh môi trường, các quỹ tự nguyện...).
- **Cơ sở vật chất & Hạ tầng:** Quản lý tài sản công, hạ tầng giao thông và các thiết chế văn hóa thể thao cấp xã.

### 6. Hệ thống & Quản trị (Admin)
- **Phân quyền người dùng (RBAC):** Quản trị tài khoản cán bộ xã với các vai trò chi tiết (Admin hệ thống, Cán bộ tư pháp, Cán bộ lao động - thương binh, Trưởng thôn/xóm...) thông qua `spatie/laravel-permission`.
- **Nhật ký hệ thống (Audit Log):** Ghi chép tự động toàn bộ thao tác thêm, sửa, xóa dữ liệu nhạy cảm của cư dân (bao gồm thông tin ai thực hiện, lúc nào, hành động và thay đổi cụ thể).
- **Dashboard & Biểu đồ trực quan:** Tổng hợp số liệu dân số, tháp tuổi, tỷ lệ lao động, tỷ lệ hộ nghèo dưới dạng biểu đồ sinh động.
- **Bộ lọc động & Xuất dữ liệu:** Cho phép cán bộ lọc dữ liệu đa điều kiện phức tạp và xuất báo cáo Excel/PDF theo mẫu hành chính của UBND xã.

---

## 🛠️ Công nghệ Sử dụng

- **Backend:** Laravel 12.x, PHP 8.2+
- **Frontend:** Laravel Blade, Tailwind CSS v4 (tích hợp qua `@tailwindcss/vite`), Vite 7
- **Database:** SQLite (mặc định cho phát triển & testing), hỗ trợ cấu hình MySQL, PostgreSQL
- **Thư viện cốt lõi:**
  - `spatie/laravel-permission`: Quản lý phân quyền vai trò (RBAC).
  - `laravel/pail`: Hỗ trợ giám sát log ứng dụng thời gian thực.
  - `laravel/pint`: Công cụ định dạng mã nguồn tự động.

---

## 🚀 Hướng dẫn Thiết lập & Chạy Dự án

### 📋 Yêu cầu hệ thống
- PHP >= 8.2 (với các extension cần thiết như: `pdo_sqlite`, `openssl`, `mbstring`, `xml`, etc.)
- Composer
- Node.js & npm

### 📥 Các bước cài đặt nhanh

1. **Clone repository về máy local:**
   ```bash
   git clone <repository_url>
   cd BTL_CNWEB
   ```

2. **Chạy tập lệnh thiết lập tự động (Setup):**
   ```bash
   composer run setup
   ```
   *Lệnh này sẽ tự động thực hiện:*
   - Cài đặt các gói phụ thuộc PHP (`composer install`).
   - Sao chép tệp `.env.example` thành `.env` (nếu chưa tồn tại).
   - Khởi tạo khóa ứng dụng (`php artisan key:generate`).
   - Tạo file cơ sở dữ liệu SQLite mặc định (`database/database.sqlite`) và chạy migrations để khởi tạo các bảng (`php artisan migrate --force`).
   - Cài đặt các thư viện frontend (`npm install`).
   - Biên dịch tài nguyên frontend (`npm run build`).

3. **Chạy Seed dữ liệu mẫu (nếu cần):**
   ```bash
   php artisan db:seed
   ```
   *Lưu ý: Dữ liệu mẫu bao gồm tài khoản cán bộ xã, sổ hộ khẩu, nhân khẩu, lịch sử làm việc, diện chính sách, nghĩa vụ quân sự... được thiết lập đúng trình tự phụ thuộc bảng.*

4. **Khởi chạy môi trường phát triển (Full Dev Stack):**
   ```bash
   composer run dev
   ```
   *Lệnh này sử dụng `concurrently` để chạy song song các dịch vụ sau:*
   - Server PHP phục vụ web (`php artisan serve` tại http://127.0.0.1:8000)
   - Lắng nghe hàng đợi (`php artisan queue:listen`)
   - Công cụ giám sát log trực quan (`php artisan pail`)
   - Vite server biên dịch frontend tức thì (`npm run dev`)

---

## 🧪 Kiểm thử (Testing) & Định dạng mã nguồn

### 🏃 Chạy Unit/Feature Tests
Hệ thống sử dụng SQLite `:memory:` cho kiểm thử đảm bảo tốc độ và không ảnh hưởng dữ liệu local. Để chạy toàn bộ test suite:
```bash
composer test
```

Nếu muốn chạy một tệp kiểm thử cụ thể:
```bash
php artisan test tests/Feature/HoKhauTest.php
```

*Trong trường hợp gặp lỗi cache khi chạy test trên Windows, sử dụng lệnh tắt cache opcache:*
```bash
php -d opcache.enable_cli=0 ./vendor/bin/phpunit --colors=never --do-not-cache-result tests/Feature/HoKhauTest.php
```

### 🧹 Định dạng Code (Linting)
Ứng dụng sử dụng Laravel Pint để định dạng mã nguồn theo tiêu chuẩn chung của Laravel. Hãy chạy lệnh sau trước khi commit hoặc tạo PR:
```bash
./vendor/bin/pint
```

---

## 📁 Cấu trúc Thư mục Quan trọng

- `app/Models/`: Nơi định nghĩa các Eloquent Model đại diện cho các bảng nghiệp vụ.
- `app/Http/Controllers/`: Chứa các controller xử lý logic nghiệp vụ và trả về views/JSON API.
- `app/Support/ModuleRegistry.php`: Bản đồ đăng ký các module hiển thị trên thanh Sidebar và Dashboard.
- `database/migrations/`: Các file migration định nghĩa cấu trúc cơ sở dữ liệu.
- `database/seeders/`: Dữ liệu mẫu phục vụ kiểm thử và phát triển nhanh.
- `resources/views/`: Chứa các template Blade của ứng dụng.
- `resources/css/app.css` & `resources/js/app.js`: Tệp cấu hình CSS (Tailwind v4) và JavaScript.
- `routes/web.php`: Nơi đăng ký toàn bộ các endpoint và phân quyền truy cập.

---

## 👥 Phân công Phát triển nhóm

Dự án được phát triển và phân rã công việc chi tiết cho nhóm 5 thành viên:
- **Người 1 (Trưởng nhóm):** Thiết lập kiến trúc hệ thống, Quản trị tài khoản, Phân quyền người dùng (RBAC), Nhật ký thao tác (Audit Log).
- **Người 2:** Quản lý Hộ khẩu, Nhân khẩu, Biến động hộ tịch (Tách/Nhập hộ), Đăng ký Tạm trú/Tạm vắng, Khai tử.
- **Người 3:** Quản lý Kinh tế, Lao động, Nơi làm việc, Doanh nghiệp & Hộ kinh doanh, Kết nối việc làm địa phương.
- **Người 4:** Quản lý An sinh xã hội (Diện chính sách, Bảo trợ), Gói trợ cấp/phát quà từ thiện, Y tế & Giáo dục.
- **Người 5:** Quản lý Nghĩa vụ quân sự (lọc độ tuổi tự động), Dân quân tự vệ, Đất đai & Thuế phí, Dashboard báo cáo và xuất Excel/PDF.
