<?php
/**
 * DEBUG VẤN ĐỀ ĐĂNG NHẬP - KIỂM TRA MẬT KHẨU
 */

echo "<h2>🔍 Debug vấn đề đăng nhập</h2>";

require_once 'bootstrap.php';

// Form test
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['test_login'])) {
    $email = sanitize($_POST['email']);
    $password = $_POST['password'];
    
    echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #007bff; margin: 15px 0;'>";
    echo "<h4>🔄 Kiểm tra đăng nhập...</h4>";
    echo "<p><strong>Email:</strong> $email</p>";
    echo "<p><strong>Password:</strong> " . str_repeat('*', strlen($password)) . "</p>";
    
    try {
        // Kiểm tra user có tồn tại không
        $nguoiDungModel = new NguoiDungModel();
        $user = $nguoiDungModel->findByEmail($email);
        
        if (!$user) {
            echo "<p>❌ <strong>Kết quả:</strong> Email không tồn tại trong database</p>";
        } else {
            echo "<p>✅ <strong>User tồn tại:</strong></p>";
            echo "<ul>";
            echo "<li><strong>ID:</strong> {$user['id']}</li>";
            echo "<li><strong>Họ tên:</strong> {$user['ho_ten']}</li>";
            echo "<li><strong>Email:</strong> {$user['email']}</li>";
            echo "<li><strong>Trạng thái:</strong> {$user['trang_thai']}</li>";
            echo "<li><strong>Vai trò ID:</strong> {$user['vai_tro_id']}</li>";
            echo "</ul>";
            
            // Kiểm tra mật khẩu
            echo "<h5>🔐 Kiểm tra mật khẩu:</h5>";
            $storedPassword = $user['mat_khau'];
            echo "<p><strong>Mật khẩu trong DB:</strong> <code>" . substr($storedPassword, 0, 20) . "...</code> (length: " . strlen($storedPassword) . ")</p>";
            
            // Test các cách hash
            $md5Hash = md5($password);
            $hashPasswordResult = hashPassword($password);
            
            echo "<p><strong>MD5 của password nhập:</strong> <code>$md5Hash</code></p>";
            echo "<p><strong>hashPassword() result:</strong> <code>$hashPasswordResult</code></p>";
            
            // So sánh
            echo "<h5>📊 Kết quả so sánh:</h5>";
            echo "<ul>";
            
            if ($storedPassword === $password) {
                echo "<li>✅ <strong>Plain text match:</strong> Có</li>";
            } else {
                echo "<li>❌ <strong>Plain text match:</strong> Không</li>";
            }
            
            if ($storedPassword === $md5Hash) {
                echo "<li>✅ <strong>MD5 match:</strong> Có</li>";
            } else {
                echo "<li>❌ <strong>MD5 match:</strong> Không</li>";
            }
            
            if ($storedPassword === $hashPasswordResult) {
                echo "<li>✅ <strong>hashPassword() match:</strong> Có</li>";
            } else {
                echo "<li>❌ <strong>hashPassword() match:</strong> Không</li>";
            }
            
            if (password_verify($password, $storedPassword)) {
                echo "<li>✅ <strong>bcrypt match:</strong> Có</li>";
            } else {
                echo "<li>❌ <strong>bcrypt match:</strong> Không</li>";
            }
            echo "</ul>";
            
            // Test login function
            echo "<h5>🧪 Test hàm login:</h5>";
            $loginResult = $nguoiDungModel->login($email, $password);
            
            if ($loginResult['success']) {
                echo "<p>✅ <strong>Login thành công!</strong></p>";
                echo "<p><strong>Thông tin user:</strong></p>";
                echo "<pre>" . print_r($loginResult['user'], true) . "</pre>";
            } else {
                echo "<p>❌ <strong>Login thất bại:</strong> {$loginResult['message']}</p>";
            }
        }
        
    } catch (Exception $e) {
        echo "<p>❌ <strong>Lỗi:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    }
    
    echo "</div>";
}

?>

<form method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 8px; margin: 20px 0;">
    <h4>🔍 Test đăng nhập</h4>
    <div style="margin-bottom: 15px;">
        <label for="email"><strong>Email:</strong></label><br>
        <input type="email" id="email" name="email" 
               placeholder="Nhập email đã đăng ký" 
               style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
    </div>
    <div style="margin-bottom: 15px;">
        <label for="password"><strong>Mật khẩu:</strong></label><br>
        <input type="password" id="password" name="password" 
               placeholder="Nhập mật khẩu đã đăng ký" 
               style="width: 300px; padding: 8px; border: 1px solid #ddd; border-radius: 4px;" required>
    </div>
    <button type="submit" name="test_login" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer;">
        Test đăng nhập
    </button>
</form>

<?php

echo "<h3>📋 Thông tin hệ thống</h3>";

echo "<h4>1. Kiểm tra hàm hash</h4>";
$testPassword = "123456";
echo "<p><strong>Test password:</strong> $testPassword</p>";
echo "<p><strong>MD5:</strong> " . md5($testPassword) . "</p>";
echo "<p><strong>hashPassword():</strong> " . hashPassword($testPassword) . "</p>";

echo "<h4>2. Kiểm tra database</h4>";
try {
    $nguoiDungModel = new NguoiDungModel();
    
    // Đếm số user
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4", DB_USER, DB_PASS);
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM nguoi_dung");
    $count = $stmt->fetch()['total'];
    echo "<p>✅ <strong>Tổng số user:</strong> $count</p>";
    
    // Lấy 5 user gần nhất
    $stmt = $pdo->query("SELECT id, email, ho_ten, LENGTH(mat_khau) as password_length, trang_thai FROM nguoi_dung ORDER BY id DESC LIMIT 5");
    $users = $stmt->fetchAll();
    
    if ($users) {
        echo "<h5>👥 5 user gần nhất:</h5>";
        echo "<table border='1' style='border-collapse: collapse; margin: 10px 0;'>";
        echo "<tr><th>ID</th><th>Email</th><th>Họ tên</th><th>Password Length</th><th>Trạng thái</th></tr>";
        foreach ($users as $user) {
            echo "<tr>";
            echo "<td>{$user['id']}</td>";
            echo "<td>{$user['email']}</td>";
            echo "<td>{$user['ho_ten']}</td>";
            echo "<td>{$user['password_length']}</td>";
            echo "<td>{$user['trang_thai']}</td>";
            echo "</tr>";
        }
        echo "</table>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ <strong>Lỗi database:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "<h3>🔧 Hướng dẫn khắc phục</h3>";
echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0;'>";
echo "<h4>⚠️ Nếu đăng nhập thất bại:</h4>";
echo "<ol>";
echo "<li><strong>Kiểm tra mật khẩu bị hash 2 lần:</strong> Nếu password length = 64, có thể bị hash 2 lần</li>";
echo "<li><strong>Kiểm tra email chính xác:</strong> Đảm bảo email nhập đúng như lúc đăng ký</li>";
echo "<li><strong>Kiểm tra trạng thái tài khoản:</strong> Phải là 'active'</li>";
echo "<li><strong>Reset mật khẩu:</strong> Sử dụng chức năng quên mật khẩu</li>";
echo "</ol>";
echo "</div>";

echo "<h3>🛠️ Sửa lỗi hash 2 lần</h3>";
echo "<p>Đã sửa trong code:</p>";
echo "<ul>";
echo "<li><strong>verify_otp.php:</strong> Thêm flag 'skip_hash' => true</li>";
echo "<li><strong>NguoiDungModel.php:</strong> Kiểm tra flag trước khi hash</li>";
echo "</ul>";

echo "<hr>";
echo "<p><strong>Hướng dẫn:</strong> Nhập email và mật khẩu đã đăng ký để kiểm tra chi tiết.</p>";
?>