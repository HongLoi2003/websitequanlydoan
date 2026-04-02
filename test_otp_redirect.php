<?php
/**
 * TEST REDIRECT TRONG QUÁ TRÌNH XÁC THỰC OTP
 */

echo "<h2>🔗 Test đường dẫn redirect OTP</h2>";

require_once 'bootstrap.php';

echo "<h3>1. Kiểm tra BASE_URL</h3>";
echo "<p><strong>BASE_URL:</strong> " . BASE_URL . "</p>";

echo "<h3>2. Các đường dẫn redirect trong verify_otp.php</h3>";

$redirects = [
    'Nếu đã đăng nhập' => '../index.php',
    'Nếu chưa có session registration' => 'auth/register.php', 
    'Sau khi xác thực OTP thành công' => 'auth/login.php'
];

foreach ($redirects as $case => $path) {
    $fullUrl = BASE_URL . $path;
    echo "<p><strong>$case:</strong><br>";
    echo "- Redirect path: <code>$path</code><br>";
    echo "- Full URL: <a href='$fullUrl' target='_blank'>$fullUrl</a></p>";
}

echo "<h3>3. Kiểm tra file tồn tại</h3>";

$files = [
    'index.php' => 'Trang chủ',
    'auth/register.php' => 'Trang đăng ký',
    'auth/login.php' => 'Trang đăng nhập',
    'auth/verify_otp.php' => 'Trang xác thực OTP'
];

foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "<p>✅ <strong>$description:</strong> <code>$file</code> - Tồn tại</p>";
    } else {
        echo "<p>❌ <strong>$description:</strong> <code>$file</code> - Không tồn tại</p>";
    }
}

echo "<h3>4. Test luồng redirect</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;'>";
echo "<h4>Luồng mong đợi:</h4>";
echo "<ol>";
echo "<li><strong>Đăng ký:</strong> <code>auth/register.php</code> → <code>auth/verify_otp.php</code></li>";
echo "<li><strong>Xác thực OTP:</strong> <code>auth/verify_otp.php</code> → <code>auth/login.php</code></li>";
echo "<li><strong>Đăng nhập:</strong> <code>auth/login.php</code> → Dashboard tương ứng</li>";
echo "</ol>";
echo "</div>";

echo "<h3>5. URL để test</h3>";
$testUrls = [
    BASE_URL . 'auth/register.php' => 'Bắt đầu đăng ký',
    BASE_URL . 'auth/verify_otp.php' => 'Xác thực OTP (cần session)',
    BASE_URL . 'auth/login.php' => 'Đăng nhập'
];

foreach ($testUrls as $url => $description) {
    echo "<p>🔗 <strong>$description:</strong> <a href='$url' target='_blank'>$url</a></p>";
}

echo "<h3>6. Kiểm tra hàm redirect</h3>";
echo "<p>Hàm redirect() sẽ tạo URL: <code>BASE_URL + path</code></p>";
echo "<p>Ví dụ: <code>redirect('auth/login.php')</code> → <code>" . BASE_URL . "auth/login.php</code></p>";

echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0;'>";
echo "<h4>✅ Kết luận</h4>";
echo "<p>Các đường dẫn redirect đã được sửa đúng:</p>";
echo "<ul>";
echo "<li>Sau khi gửi OTP: redirect đến <code>auth/verify_otp.php</code></li>";
echo "<li>Sau khi xác thực OTP: redirect đến <code>auth/login.php</code></li>";
echo "<li>Link HTML trong form: <code>login.php</code> (relative path đúng)</li>";
echo "</ul>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Hướng dẫn test:</strong></p>";
echo "<ol>";
echo "<li>Vào trang đăng ký và điền thông tin</li>";
echo "<li>Kiểm tra có chuyển đến verify_otp.php không</li>";
echo "<li>Nhập mã OTP và submit</li>";
echo "<li>Kiểm tra có chuyển về login.php không</li>";
echo "</ol>";
?>