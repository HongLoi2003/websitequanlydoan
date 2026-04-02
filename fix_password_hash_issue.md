# 🔐 SỬA LỖI MẬT KHẨU BỊ HASH 2 LẦN

## ❌ Vấn đề
Sau khi đăng ký và xác thực OTP thành công, đăng nhập báo "Mật khẩu không đúng".

## 🎯 Nguyên nhân
Mật khẩu bị hash 2 lần:
1. Lần 1: trong register.php
2. Lần 2: trong NguoiDungModel->createUser()

## ✅ Đã sửa
1. **verify_otp.php**: Thêm flag `skip_hash => true`
2. **NguoiDungModel.php**: Kiểm tra flag trước khi hash

## 🧪 Cách test
```bash
php debug_login_password.php
```

Nhập email và mật khẩu để kiểm tra chi tiết.

## 🔄 Kết quả
- ✅ Đăng ký mới: Password chỉ hash 1 lần
- ✅ Đăng nhập: Thành công với mật khẩu đã tạo