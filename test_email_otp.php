<?php
/**
 * TEST GỬI EMAIL OTP
 */

echo "<h2>📧 Test gửi email OTP</h2>";

require_once 'bootstrap.php';

echo "<h3>1. Kiểm tra cấu hình SMTP</h3>";

$smtpConfig = [
    'SMTP_HOST' => SMTP_HOST,
    'SMTP_PORT' => SMTP_PORT,
    'SMTP_USER' => SMTP_USER,
    'SMTP_FROM' => SMTP_FROM,
    'SMTP_FROM_NAME' => SMTP_FROM_NAME,
    'DEVELOPMENT_MODE' => DEVELOPMENT_MODE ? 'true' : 'false'
];

echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
echo "<tr><th>Cấu hình</th><th>Giá trị</th></tr>";
foreach ($smtpConfig as $key => $value) {
    if ($key === 'SMTP_PASS') {
        $value = str_repeat('*', strlen($value)); // Ẩn password
    }
    echo "<tr><td><strong>$key</strong></td><td>$value</td></tr>";
}
echo "</table>";

echo "<h3>2. Kiểm tra PHPMailer</h3>";

if (file_exists('vendor/autoload.php')) {
    echo "<p>✅ <strong>Composer autoload:</strong> Tồn tại</p>";
    
    if (file_exists('vendor/phpmailer/phpmailer')) {
        echo "<p>✅ <strong>PHPMailer:</strong> Đã cài đặt</p>";
    } else {
        echo "<p>❌ <strong>PHPMailer:</strong> Chưa cài đặt</p>";
    }
} else {
    echo "<p>❌ <strong>Composer:</strong> Chưa chạy composer install</p>";
}

echo "<h3>3. Test gửi email thật</h3>";

// Form test email
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_email'])) {
    $testEmail = sanitize($_POST['test_email']);
    $testOTP = generateOTP(6);
    
    echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;'>";
    echo "<h4>🔄 Đang gửi email test...</h4>";
    echo "<p><strong>Email:</strong> $testEmail</p>";
    echo "<p><strong>OTP:</strong> $testOTP</p>";
    
    try {
        $result = sendOTPEmail($testEmail, $testOTP, 'Test User');
        
        if ($result) {
            echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h4>✅ Gửi email thành công!</h4>";
            echo "<p>Kiểm tra hộp thư (và spam) của email: <strong>$testEmail</strong></p>";
            echo "</div>";
        } else {
            echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
            echo "<h4>❌ Gửi email thất bại!</h4>";
            echo "<p>Kiểm tra log lỗi để xem chi tiết</p>";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>❌ Lỗi exception:</h4>";
        echo "<p>" . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
    
    echo "</div>";
}

?>

<form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h4>📧 Test gửi email OTP</h4>
    <div style="margin-bottom: 15px;">
        <label for="test_email"><strong>Email nhận test:</strong></label><br>
        <input type="email" id="test_email" name="test_email" 
               placeholder="Nhập email để test gửi OTP" 
               style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
    </div>
    <button type="submit" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
        Gửi email test
    </button>
</form>

<?php

echo "<h3>4. Kiểm tra log lỗi</h3>";
echo "<p>Nếu có lỗi, kiểm tra:</p>";
echo "<ul>";
echo "<li><strong>PHP Error Log:</strong> Xem trong XAMPP/logs/php_error_log</li>";
echo "<li><strong>Apache Error Log:</strong> Xem trong XAMPP/logs/error.log</li>";
echo "<li><strong>Browser Console:</strong> F12 → Console tab</li>";
echo "</ul>";

echo "<h3>5. Các vấn đề thường gặp</h3>";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0;'>";
echo "<h4>⚠️ Nếu email không gửi được:</h4>";
echo "<ol>";
echo "<li><strong>Gmail App Password:</strong> Đảm bảo đã tạo App Password 16 ký tự</li>";
echo "<li><strong>2-Factor Authentication:</strong> Phải bật 2FA trước khi tạo App Password</li>";
echo "<li><strong>Firewall:</strong> Kiểm tra port 587 có bị chặn không</li>";
echo "<li><strong>OpenSSL:</strong> Đảm bảo PHP có extension OpenSSL</li>";
echo "<li><strong>Composer:</strong> Chạy <code>composer install</code> nếu chưa có vendor/</li>";
echo "</ol>";
echo "</div>";

echo "<h3>6. Hướng dẫn tạo Gmail App Password</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;'>";
echo "<ol>";
echo "<li>Vào <a href='https://myaccount.google.com/' target='_blank'>Google Account</a></li>";
echo "<li>Chọn <strong>Security</strong> → <strong>2-Step Verification</strong> (bật nếu chưa có)</li>";
echo "<li>Chọn <strong>App passwords</strong></li>";
echo "<li>Tạo password mới cho <strong>Mail</strong></li>";
echo "<li>Copy mã 16 ký tự vào <code>SMTP_PASS</code> trong config/config.php</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Lưu ý:</strong> Sau khi sửa cấu hình, hãy test lại bằng form trên.</p>";
?>