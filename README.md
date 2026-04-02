# HỆ THỐNG QUẢN LÝ ĐỀ TÀI (QLĐT)

Hệ thống quản lý đề tài cho giảng viên, sinh viên và lãnh đạo khoa.

## CÔNG NGHỆ SỬ DỤNG

- **Backend**: PHP 7.4+ (thuần, không framework)
- **Database**: MySQL 5.7+
- **Frontend**: Bootstrap 5.3, jQuery 3.7
- **Architecture**: MVC Pattern đơn giản

## CẤU TRÚC DỰ ÁN

```
qldt/
├── assets/
│   ├── css/
│   │   └── style.css          # CSS tùy chỉnh
│   └── js/
│       └── main.js            # JavaScript chung
├── auth/
│   ├── login.php              # Đăng nhập
│   ├── register.php           # Đăng ký (bước 1)
│   ├── verify_otp.php         # Xác thực OTP (bước 2)
│   └── logout.php             # Đăng xuất
├── config/
│   ├── config.php             # Cấu hình hệ thống
│   └── database.php           # Kết nối database (Singleton Pattern)
├── database/
│   └── qldt.sql               # Database schema + sample data
├── giang_vien/
│   ├── dashboard.php          # Dashboard giảng viên
│   ├── danh_sach_de_tai.php   # Danh sách đề tài
│   ├── tao_de_tai.php         # Tạo đề tài mới
│   ├── duyet_sinh_vien.php    # Duyệt sinh viên đăng ký
│   └── danh_sach_sinh_vien.php # Sinh viên đã duyệt
├── sinh_vien/
│   ├── dashboard.php          # Dashboard sinh viên
│   ├── danh_sach_de_tai.php   # Xem đề tài có thể đăng ký
│   ├── dang_ky_de_tai.php     # Đăng ký đề tài
│   └── de_tai_cua_toi.php     # Đề tài đã đăng ký
├── lanh_dao/
│   ├── dashboard.php          # Dashboard lãnh đạo
│   ├── duyet_de_tai.php       # Duyệt đề tài giảng viên
│   ├── danh_sach_phan_cong.php # Xem phân công
│   └── xuat_bao_cao.php       # Xuất PDF/Excel
├── helpers/
│   ├── functions.php          # Hàm tiện ích
│   └── email.php              # Gửi email
├── includes/
│   ├── header.php             # Header chung
│   └── footer.php             # Footer chung
├── models/
│   ├── BaseModel.php          # Model cơ sở
│   ├── NguoiDungModel.php     # Model người dùng
│   ├── VaiTroModel.php        # Model vai trò
│   ├── OTPModel.php           # Model OTP
│   ├── GiangVienModel.php     # Model giảng viên
│   ├── SinhVienModel.php      # Model sinh viên
│   ├── LanhDaoModel.php       # Model lãnh đạo
│   ├── DeTaiModel.php         # Model đề tài
│   ├── DangKyDeTaiModel.php   # Model đăng ký đề tài
│   └── ThongBaoModel.php      # Model thông báo
├── bootstrap.php              # Load tất cả dependencies
├── index.php                  # Trang chủ (routing)
└── README.md                  # Tài liệu này
```

## CÀI ĐẶT

### 1. Yêu cầu hệ thống

- PHP >= 7.4
- MySQL >= 5.7
- Apache/Nginx với mod_rewrite
- Extension: PDO, PDO_MySQL, mbstring

### 2. Cài đặt database

```sql
-- Import file database
mysql -u root -p < database/qldt.sql
```

Hoặc import qua phpMyAdmin.

### 3. Cấu hình

Chỉnh sửa file `config/database.php`:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'qldt_database');
define('DB_USER', 'root');
define('DB_PASS', '');
```

Chỉnh sửa file `config/config.php`:

```php
define('BASE_URL', 'http://localhost/qldt/');
```

### 4. Chạy ứng dụng

Truy cập: `http://localhost/qldt/`

## TÀI KHOẢN DEMO

| Vai trò | Email | Mật khẩu |
|---------|-------|----------|
| Giảng viên | gv.nguyenvana@example.com | 123456 |
| Sinh viên | sv.nguyenvanb@example.com | 123456 |
| Lãnh đạo | ld.nguyenvanx@example.com | 123456 |

## LUỒNG HOẠT ĐỘNG

### GIAI ĐOẠN 1: Đăng ký & Đăng nhập
1. Người dùng đăng ký tài khoản
2. Hệ thống gửi OTP qua email
3. Xác thực OTP để hoàn tất đăng ký
4. Đăng nhập vào hệ thống

### GIAI ĐOẠN 2: Giảng viên tạo đề tài
- Mỗi giảng viên BẮT BUỘC tạo:
  - 10 đề tài Cơ sở ngành
  - 10 đề tài Chuyên ngành
- Trạng thái ban đầu: `nhap`
- Gửi chờ duyệt: `cho_duyet`

### GIAI ĐOẠN 3: Lãnh đạo duyệt đề tài
- Xem danh sách đề tài `cho_duyet`
- **Duyệt**: Chuyển sang `da_duyet` → Hiển thị cho sinh viên
- **Từ chối**: Chuyển sang `tu_choi` + lý do → Giảng viên chỉnh sửa

### GIAI ĐOẠN 4: Sinh viên đăng ký đề tài
- Chỉ thấy đề tài `da_duyet`
- Đăng ký đề tài còn chỗ trống
- Trạng thái: `cho_duyet`

### GIAI ĐOẠN 5: Giảng viên duyệt sinh viên
- Xem danh sách sinh viên đăng ký
- **Duyệt**: Chuyển sang `da_duyet` + tăng số lượng đã đăng ký
- **Từ chối**: Chuyển sang `tu_choi` + lý do

### GIAI ĐOẠN 6: Hoàn tất & Xuất báo cáo
- Khi giảng viên duyệt đủ 20 sinh viên (10 CSN + 10 CN)
- Lãnh đạo xem tổng hợp toàn khoa
- Xuất PDF/Excel

## DATABASE SCHEMA

### Bảng chính

1. **nguoi_dung**: Thông tin đăng nhập
2. **vai_tro**: Giảng viên, Sinh viên, Lãnh đạo
3. **giang_vien**: Profile giảng viên
4. **sinh_vien**: Profile sinh viên
5. **lanh_dao**: Profile lãnh đạo
6. **de_tai**: Đề tài
7. **dang_ky_de_tai**: Đăng ký đề tài
8. **lich_su_duyet_de_tai**: Lịch sử duyệt
9. **thong_bao**: Thông báo hệ thống
10. **otp_verification**: OTP xác thực email

### Trạng thái

**Đề tài:**
- `nhap`: Đang soạn thảo
- `cho_duyet`: Chờ lãnh đạo duyệt
- `da_duyet`: Đã duyệt, hiển thị cho sinh viên
- `tu_choi`: Bị từ chối

**Đăng ký:**
- `cho_duyet`: Chờ giảng viên duyệt
- `da_duyet`: Đã duyệt
- `tu_choi`: Bị từ chối

## API LOGIC (Internal)

### Authentication
- `NguoiDungModel::login($email, $password)` - Đăng nhập
- `OTPModel::createOTP($email, $vaiTro, $data)` - Tạo OTP
- `OTPModel::verifyOTP($email, $otpCode)` - Xác thực OTP

### Giảng viên
- `GiangVienModel::getThongKeDeTai($giangVienId)` - Thống kê đề tài
- `DeTaiModel::createDeTai($data)` - Tạo đề tài
- `DeTaiModel::guiChoDuyet($deTaiId)` - Gửi đề tài chờ duyệt
- `DangKyDeTaiModel::duyetSinhVien($dangKyId, $nguoiDuyetId)` - Duyệt SV
- `DangKyDeTaiModel::tuChoiSinhVien($dangKyId, $lyDo)` - Từ chối SV

### Sinh viên
- `DeTaiModel::getDeTaiDaDuyet($filters)` - Xem đề tài có thể đăng ký
- `DangKyDeTaiModel::dangKyDeTai($sinhVienId, $deTaiId)` - Đăng ký đề tài
- `SinhVienModel::getDeTaiDaDangKy($sinhVienId)` - Xem đề tài đã đăng ký

### Lãnh đạo
- `DeTaiModel::getDeTaiChoDuyet()` - Xem đề tài chờ duyệt
- `DeTaiModel::duyetDeTai($deTaiId, $nguoiDuyetId)` - Duyệt đề tài
- `DeTaiModel::tuChoiDeTai($deTaiId, $nguoiDuyetId, $lyDo)` - Từ chối đề tài
- `DangKyDeTaiModel::getDanhSachPhanCong()` - Danh sách phân công
- `DangKyDeTaiModel::getThongKePhanCongTheoGiangVien()` - Thống kê theo GV

### Thông báo
- `ThongBaoModel::taoThongBao($nguoiNhanId, $tieuDe, $noiDung, $loai, $link)`
- `ThongBaoModel::thongBaoDeTaiDuocDuyet($giangVienId, $tenDeTai)`
- `ThongBaoModel::thongBaoSinhVienDangKy($giangVienId, $tenSinhVien, $tenDeTai)`

## BẢO MẬT

- Mật khẩu: MD5 hash (theo yêu cầu database)
- Session-based authentication
- CSRF token protection (có sẵn trong helper)
- Input sanitization
- Prepared statements (PDO)

## LƯU Ý

1. **Email OTP**: Trong môi trường development, cần cấu hình SMTP trong `config/config.php`
2. **Số lượng đề tài**: Có thể thay đổi trong `config/config.php`:
   - `SO_DE_TAI_CO_SO_NGANH = 10`
   - `SO_DE_TAI_CHUYEN_NGANH = 10`
3. **Session timeout**: Mặc định 1 giờ, có thể thay đổi `SESSION_TIMEOUT`

## PHÁT TRIỂN THÊM

Các tính năng có thể mở rộng:
- [ ] Xuất PDF/Excel báo cáo
- [ ] Upload file đính kèm đề tài
- [ ] Chat giữa giảng viên và sinh viên
- [ ] Đánh giá và nhận xét đề tài
- [ ] Lịch sử chỉnh sửa đề tài
- [ ] API RESTful cho mobile app
- [ ] Real-time notifications (WebSocket)

## HỖ TRỢ

Nếu gặp vấn đề, kiểm tra:
1. PHP error log
2. MySQL error log
3. Browser console
4. Network tab (F12)

---

**Phiên bản**: 1.0.0  
**Ngày cập nhật**: 2024
