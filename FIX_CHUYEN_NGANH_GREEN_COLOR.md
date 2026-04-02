# 🔧 SỬA LỖI MÀU XANH LÁ CÂY CHO TAB CHUYÊN NGÀNH

## ❌ Vấn đề
Tab "Chuyên ngành" vẫn hiển thị màu xanh dương thay vì màu xanh lá cây khi được chọn.

## ✅ Giải pháp đã áp dụng

### 1. CSS với độ ưu tiên cao (!important)
```css
/* CSS riêng cho tab Chuyên ngành khi active - Độ ưu tiên cao */
.nav-tabs .nav-link.active[href="#chuyen-nganh"] {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3) !important;
}

/* Selector bổ sung để đảm bảo hoạt động */
ul.nav.nav-tabs li .nav-link.active[href="#chuyen-nganh"] {
    background: linear-gradient(135deg, #28a745 0%, #20c997 100%) !important;
    color: white !important;
    box-shadow: 0 4px 12px rgba(40, 167, 69, 0.3) !important;
}
```

### 2. JavaScript xử lý khi click tab
```javascript
// Xử lý màu sắc cho tab Chuyên ngành
if (this.getAttribute('href') === '#chuyen-nganh') {
    // Xóa class active khỏi tất cả tab
    document.querySelectorAll('.nav-link').forEach(t => t.classList.remove('active'));
    // Thêm class active cho tab hiện tại
    this.classList.add('active');
    // Force apply green color
    this.style.background = 'linear-gradient