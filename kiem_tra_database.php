<?php
/**
 * KIỂM TRA VÀ SỬA LỖI DATABASE
 * Kiểm tra các bảng thiếu và tạo nếu cần
 */

echo "<h2>🔧 Kiểm tra và sửa lỗi database</h2>";

// Thông tin kết nối database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'qldt'; // Thay đổi nếu tên database khác

try {
    // Kết nối database
    $pdo = new PDO("mysql:host=$host;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<p>✅ Kết nối MySQL thành công</p>";
    
    // Kiểm tra database có tồn tại không
    echo "<h3>1. Kiểm tra database</h3>";
    
    $stmt = $pdo->query("SHOW DATABASES LIKE '$database'");
    if ($stmt->rowCount() == 0) {
        echo "<p>⚠️ Database '$database' không tồn tại. Đang tạo...</p>";
        $pdo->exec("CREATE DATABASE `$database` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        echo "<p>✅ Đã tạo database '$database'</p>";
    } else {
        echo "<p>✅ Database '$database' đã tồn tại</p>";
    }
    
    // Chọn database
    $pdo->exec("USE `$database`");
    
    // Kiểm tra các bảng
    echo "<h3>2. Kiểm tra các bảng</h3>";
    
    $requiredTables = [
        'vai_tro',
        'nguoi_dung', 
        'giang_vien',
        'sinh_vien',
        'lanh_dao',
        'de_tai',
        'dang_ky_de_tai',
        'thong_bao',
        'thong_bao_do_an',
        'cai_dat',
        'otp_verification',
        'password_resets'
    ];
    
    $stmt = $pdo->query("SHOW TABLES");
    $existingTables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    $missingTables = array_diff($requiredTables, $existingTables);
    
    if (empty($missingTables)) {
        echo "<p>✅ Tất cả bảng cần thiết đã tồn tại</p>";
        
        echo "<div style='background: #e7f3ff; padding: 10px; margin: 10px 0;'>";
        echo "<strong>Danh sách bảng hiện có:</strong><br>";
        foreach ($existingTables as $table) {
            echo "- $table<br>";
        }
        echo "</div>";
        
    } else {
        echo "<p>⚠️ Thiếu " . count($missingTables) . " bảng:</p>";
        echo "<ul>";
        foreach ($missingTables as $table) {
            echo "<li style='color: red;'>$table</li>";
        }
        echo "</ul>";
        
        echo "<div style='background: #fff3cd; padding: 15px; border-left: 4px solid #ffc107; margin: 15px 0;'>";
        echo "<h4>🔧 Cách khắc phục:</h4>";
        echo "<ol>";
        echo "<li>Mở <strong>phpMyAdmin</strong> (http://localhost/phpmyadmin)</li>";
        echo "<li>Chọn database <strong>'$database'</strong></li>";
        echo "<li>Vào tab <strong>'SQL'</strong></li>";
        echo "<li>Copy nội dung file <strong>'sua_loi_database.sql'</strong> và paste vào</li>";
        echo "<li>Click <strong>'Go'</strong> để thực thi</li>";
        echo "<li>Sau đó chạy lại script này để kiểm tra</li>";
        echo "</ol>";
        echo "</div>";
    }
    
    // Kiểm tra dữ liệu cơ bản
    if (in_array('vai_tro', $existingTables)) {
        echo "<h3>3. Kiểm tra dữ liệu vai trò</h3>";
        
        $stmt = $pdo->query("SELECT COUNT(*) FROM vai_tro");
        $vaiTroCount = $stmt->fetchColumn();
        
        if ($vaiTroCount == 0) {
            echo "<p>⚠️ Chưa có dữ liệu vai trò. Đang thêm...</p>";
            
            $pdo->exec("
                INSERT INTO vai_tro (ma_vai_tro, ten_vai_tro, mo_ta) VALUES
                ('giang_vien', 'Giảng viên', 'Giảng viên hướng dẫn đề tài'),
                ('sinh_vien', 'Sinh viên', 'Sinh viên thực hiện đề tài'),
                ('lanh_dao', 'Lãnh đạo', 'Lãnh đạo khoa/bộ môn')
            ");
            
            echo "<p>✅ Đã thêm dữ liệu vai trò</p>";
        } else {
            echo "<p>✅ Đã có $vaiTroCount vai trò trong hệ thống</p>";
        }
    }
    
    // Kiểm tra giảng viên
    if (in_array('giang_vien', $existingTables) && in_array('nguoi_dung', $existingTables)) {
        echo "<h3>4. Kiểm tra giảng viên</h3>";
        
        $stmt = $pdo->query("
            SELECT COUNT(*) FROM giang_vien gv 
            JOIN nguoi_dung nd ON gv.nguoi_dung_id = nd.id
        ");
        $giangVienCount = $stmt->fetchColumn();
        
        if ($giangVienCount == 0) {
            echo "<p>⚠️ Chưa có giảng viên. Hãy chạy <code>php import_24_fixed.php</code></p>";
        } else {
            echo "<p>✅ Đã có $giangVienCount giảng viên trong hệ thống</p>";
        }
    }
    
    // Tóm tắt
    echo "<h3>5. Tóm tắt</h3>";
    
    if (empty($missingTables)) {
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px;'>";
        echo "<h4>🎉 Database đã sẵn sàng!</h4>";
        echo "<p>Tất cả bảng cần thiết đã có. Bạn có thể:</p>";
        echo "<ul>";
        echo "<li>Chạy <code>php import_24_fixed.php</code> để thêm 24 giảng viên</li>";
        echo "<li>Chạy <code>tao_de_tai_hang_loat.sql</code> để tạo đề tài</li>";
        echo "<li>Sử dụng hệ thống bình thường</li>";
        echo "</ul>";
        echo "</div>";
    } else {
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "<h4>❌ Database chưa đầy đủ</h4>";
        echo "<p>Hãy chạy file <strong>sua_loi_database.sql</strong> trong phpMyAdmin trước</p>";
        echo "</div>";
    }
    
} catch (PDOException $e) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<h4>❌ Lỗi kết nối database</h4>";
    echo "<p><strong>Chi tiết:</strong> " . $e->getMessage() . "</p>";
    echo "<p><strong>Khắc phục:</strong></p>";
    echo "<ul>";
    echo "<li>Kiểm tra XAMPP/WAMP đã khởi động MySQL chưa</li>";
    echo "<li>Kiểm tra thông tin kết nối (host, username, password)</li>";
    echo "<li>Tạo database '$database' nếu chưa có</li>";
    echo "</ul>";
    echo "</div>";
}

echo "<hr>";
echo "<h3>📋 Hướng dẫn tiếp theo</h3>";
echo "<ol>";
echo "<li><strong>Nếu có lỗi:</strong> Chạy <code>sua_loi_database.sql</code> trong phpMyAdmin</li>";
echo "<li><strong>Thêm giảng viên:</strong> Chạy <code>php import_24_fixed.php</code></li>";
echo "<li><strong>Tạo đề tài:</strong> Chạy <code>tao_de_tai_hang_loat.sql</code> trong phpMyAdmin</li>";
echo "<li><strong>Kiểm tra:</strong> Vào các trang dashboard để xem kết quả</li>";
echo "</ol>";
?>