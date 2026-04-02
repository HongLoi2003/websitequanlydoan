<?php
/**
 * TEST LUỒNG ĐĂNG KÝ - KIỂM TRA ĐƯỜNG DẪN
 */

echo "<h2>🧪 Test luồng đăng ký sinh viên</h2>";

// Kiểm tra các file có tồn tại không
$files = [
    'auth/register.php' => 'Trang đăng ký',
    'auth/verify_otp.php' => 'Trang xác thực OTP', 
    'auth/login.php' => 'Trang đăng nhập'
];

echo "<h3>1. Kiểm tra file tồn tại</h3>";
foreach ($files as $file => $description) {
    if (file_exists($file)) {
        echo "<p>✅ <strong>$description:</strong> <code>$file</code> - Tồn tại</p>";
    } else {
        echo "<p>❌ <strong>$description:</strong> <code>$file</code> - Không tồn tại</p>";
    }
}

echo "<h3>2. Kiểm tra đường dẫn URL</h3>";
$baseUrl = 'http://localhost/WebsiteQuanLyDeTai/';
$urls = [
    $baseUrl . 'auth/register.php' => 'Đăng ký sinh viên',
    $baseUrl . 'auth/verify_otp.php' => 'Xác thực OTP',
    $baseUrl . 'auth/login.php' => 'Đăng nhập'
];

foreach ($urls as $url => $description) {
    echo "<p>🔗 <strong>$description:</strong> <a href='$url' target='_blank'>$url</a></p>";
}

echo "<h3>3. Luồng hoạt động mong đợi</h3>";
echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;'>";
echo "<ol>";
echo "<li><strong>Bước 1:</strong> Sinh viên vào <code>auth/register.php</code> để đăng ký</li>";
echo "<li><strong>Bước 2:</strong> Sau khi điền form, hệ thống gửi OTP và chuyển đến <code>auth/verify_otp.php</code></li>";
echo "<li><strong>Bước 3:</strong> Sinh viên nhập mã OTP, sau khi xác thực thành công chuyển về <code>auth/login.php</code></li>";
echo "<li><strong>Bước 4:</strong> Sinh viên đăng nhập với tài khoản vừa tạo</li>";
echo "</ol>";
echo "</div>";

echo "<h3>4. Kiểm tra cấu hình</h3>";

// Kiểm tra BASE_URL
if (defined('BASE_URL')) {
    echo "<p>✅ <strong>BASE_URL:</strong> " . BASE_URL . "</p>";
} else {
    require_once 'bootstrap.php';
    echo "<p>✅ <strong>BASE_URL:</strong> " . BASE_URL . " (loaded from bootstrap)</p>";
}

// Kiểm tra database connection
try {
    require_once 'config/database.php';
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8mb4", $username, $password);
    echo "<p>✅ <strong>Database:</strong> Kết nối thành công</p>";
    
    // Kiểm tra bảng otp_verification
    $stmt = $pdo->query("SHOW TABLES LIKE 'otp_verification'");
    if ($stmt->rowCount() > 0) {
        echo "<p>✅ <strong>Bảng OTP:</strong> otp_verification tồn tại</p>";
        
        // Kiểm tra cấu trúc
        $stmt = $pdo->query("DESCRIBE otp_verification");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (in_array('data', $columns)) {
            echo "<p>✅ <strong>Cột 'data':</strong> Tồn tại</p>";
        } else {
            echo "<p>❌ <strong>Cột 'data':</strong> Không tồn tại - Cần chạy fix_otp_database.php</p>";
        }
        
        if (in_array('is_verified', $columns)) {
            echo "<p>✅ <strong>Cột 'is_verified':</strong> Tồn tại</p>";
        } else {
            echo "<p>❌ <strong>Cột 'is_verified':</strong> Không tồn tại - Cần chạy fix_otp_database.php</p>";
        }
    } else {
        echo "<p>❌ <strong>Bảng OTP:</strong> otp_verification không tồn tại</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Database:</strong> " . $e->getMessage() . "</p>";
}

echo "<h3>5. Hướng dẫn test</h3>";
echo "<div style='background: #d4edda; padding: 15px; border-left: 4px solid #28a745; margin: 15px 0;'>";
echo "<ol>";
echo "<li>Vào <a href='{$baseUrl}auth/register.php' target='_blank'>trang đăng ký</a></li>";
echo "<li>Điền thông tin sinh viên và submit</li>";
echo "<li>Kiểm tra có chuyển đến trang verify_otp.php không</li>";
echo "<li>Nhập mã OTP (trong development mode sẽ hiển thị mã)</li>";
echo "<li>Kiểm tra có chuyển về trang login.php không</li>";
echo "<li>Thử đăng nhập với tài khoản vừa tạo</li>";
echo "</ol>";
echo "</div>";

echo "<hr>";
echo "<p><strong>Lưu ý:</strong> Nếu có lỗi, hãy chạy <code>php fix_otp_database.php</code> trước khi test.</p>";
?>