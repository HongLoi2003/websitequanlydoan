# 🎨 CẬP NHẬT MÀU TAB CHUYÊN NGÀNH

## ✅ Thay đổi đã thực hiện

### File: `lanh_dao/quan_ly_noi_dung_do_an.php`

**Mục tiêu:** Khi bấm vào tab "Chuyên ngành" sẽ chuyển sang màu xanh lá cây thay vì màu xanh dương mặc định.

## 🎨 CSS đã thêm

### 1. Tab active - Màu xanh lá cây
```css
/* CSS riêng cho tab Chuyên ngành khi active */
.nav-tabs .nav-link.active[href="#chuyen-nganh"] {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color: white;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3);
}
```

### 2. Hover effect - Màu xanh lá cây nhạt
```css
/* Hover effect riêng cho tab Chuyên ngành */
.nav-tabs .nav-link[href="#chuyen-nganh"]:hover:not(.active) {
    background: #d4edda;
    color: #155724;
    transform: translateY(-2px);
}
```

## 🎯 Kết quả

### Trước khi sửa:
- **Tab active:** Màu xanh dương (#0d6efd)
- **Tab hover:** Màu xám (#e9ecef)

### Sau khi sửa:
- **Tab "Chuyên ngành" active:** Màu xanh lá cây gradient (#28a745 → #20c997)
- **Tab "Chuyên ngành" hover:** Màu xanh lá cây nhạt (#d4edda)
- **Các tab khác:** Giữ nguyên màu xanh dương

## 🔧 Chi tiết màu sắc

### Màu xanh lá cây sử dụng:
- **Primary Green:** `#28a745` (Bootstrap success color)
- **Secondary Green:** `#20c997` (Bootstrap teal color)
- **Light Green:** `#d4edda` (Bootstrap success light)
- **Dark Green Text:** `#155724` (Bootstrap success dark)

### Shadow effect:
- **Box shadow:** `0 4px 12px rgba(40, 167, 69, 0.3)` (màu xanh lá với độ trong suốt)

## 📋 Cách hoạt động

1. **Khi chưa chọn:** Tab "Chuyên ngành" có màu mặc định
2. **Khi hover:** Tab chuyển sang màu xanh lá cây nhạt với hiệu ứng nâng lên
3. **Khi active:** Tab có màu xanh lá cây gradient với shadow xanh
4. **Các tab khác:** Vẫn giữ màu xanh dương như cũ

## ✨ Tính năng

- ✅ **Responsive:** Hoạt động trên mọi kích thước màn hình
- ✅ **Smooth transition:** Hiệu ứng chuyển màu mượt mà (0.3s)
- ✅ **Accessibility:** Màu sắc có độ tương phản tốt
- ✅ **Consistent:** Giữ nguyên style của các tab khác

---

**Hoàn thành! Tab "Chuyên ngành" bây giờ sẽ có màu xanh lá cây khi được chọn. 🌿✅**