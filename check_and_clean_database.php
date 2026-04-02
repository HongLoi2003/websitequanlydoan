<?php
/**
 * KIỂM TRA VÀ XÓA DATABASE - PHIÊN BẢN MYSQLI
 * Sử dụng mysqli thay vì PDO
 */

// Cấu hình database
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'qldt';

echo "<h2>🔍 Kiểm tra và xóa database</h2>";

// Thử kết nối với mysqli
$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
    echo "<h4>❌ Lỗi kết nối database</h4>";
    echo "<p>Chi tiết: " . $conn->connect_error . "</p>";
    echo "<p><strong>Hướng dẫn khắc phục:</strong></p>";
    echo "<ol>";
    echo "<li>Kiểm tra XAMPP/WAMP đã khởi động MySQL chưa</li>";
    echo "<li>Kiểm tra tên database có đúng là 'qldt' không</li>";
    echo "<li>Kiểm tra username/password MySQL</li>";
    echo "</ol>";
    echo "</div>";
    exit;
}

echo "<p>✅ Kết nối database thành công!</p>";

// 1. Kiểm tra tài khoản test hiện tại
echo "<h3>1. Tài khoản test hiện tại</h3>";

$sql = "SELECT id, ho_ten, email, vai_tro_id FROM nguoi_dung 
        WHERE ho_ten LIKE 'GV.%' 
           OR email LIKE '%example.com%' 
           OR ho_ten LIKE '%Test%'
        ORDER BY id";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<p>🔍 Tìm thấy " . $result->num_rows . " tài khoản test:</p>";
    
    echo "<table border='1' style='border-collapse: collapse; width: 100%; margin: 10px 0;'>";
    echo "<tr style='background: #f0f0f0;'>";
    echo "<th>ID</th><th>Họ tên</th><th>Email</th><th>Vai trò ID</th>";
    echo "</tr>";
    
    $testAccounts = [];
    while ($row = $result->fetch_assoc()) {
        $testAccounts[] = $row;
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td style='background: yellow; font-weight: bold;'>{$row['ho_ten']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['vai_tro_id']}</td>";
        echo "</tr>";
    }
    echo "</table>";
    
    // 2. Xóa tài khoản test
    echo "<h3>2. Xóa tài khoản test</h3>";
    
    // Bắt đầu transaction
    $conn->autocommit(FALSE);
    
    try {
        $deletedCount = 0;
        
        foreach ($testAccounts as $account) {
            $userId = $account['id'];
            $userName = $account['ho_ten'];
            
            // Xóa đề tài của giảng viên test
            $sql = "DELETE dt FROM de_tai dt
                    JOIN giang_vien gv ON dt.giang_vien_id = gv.id
                    WHERE gv.nguoi_dung_id = $userId";
            $conn->query($sql);
            
            // Xóa đăng ký đề tài của sinh viên test
            $sql = "DELETE dk FROM dang_ky_de_tai dk
                    JOIN sinh_vien sv ON dk.sinh_vien_id = sv.id
                    WHERE sv.nguoi_dung_id = $userId";
            $conn->query($sql);
            
            // Xóa từ bảng giang_vien
            $sql = "DELETE FROM giang_vien WHERE nguoi_dung_id = $userId";
            $conn->query($sql);
            
            // Xóa từ bảng sinh_vien
            $sql = "DELETE FROM sinh_vien WHERE nguoi_dung_id = $userId";
            $conn->query($sql);
            
            // Xóa từ bảng lanh_dao
            $sql = "DELETE FROM lanh_dao WHERE nguoi_dung_id = $userId";
            $conn->query($sql);
            
            // Xóa từ bảng nguoi_dung
            $sql = "DELETE FROM nguoi_dung WHERE id = $userId";
            if ($conn->query($sql)) {
                echo "<p>✅ Đã xóa: <strong>$userName</strong> (ID: $userId)</p>";
                $deletedCount++;
            }
        }
        
        // Commit transaction
        $conn->commit();
        
        echo "<div style='background: #d4edda; padding: 15px; border-radius: 5px; margin: 15px 0;'>";
        echo "<h4>🎉 Hoàn thành!</h4>";
        echo "<p>Đã xóa thành công <strong>$deletedCount</strong> tài khoản test.</p>";
        echo "<p><strong>GV. Test sẽ không còn xuất hiện trong dropdown nữa!</strong></p>";
        echo "</div>";
        
    } catch (Exception $e) {
        $conn->rollback();
        echo "<div style='background: #f8d7da; padding: 15px; border-radius: 5px;'>";
        echo "<h4>❌ Lỗi khi xóa</h4>";
        echo "<p>" . $e->getMessage() . "</p>";
        echo "</div>";
    }
    
    $conn->autocommit(TRUE);
    
} else {
    echo "<p>✅ Không tìm thấy tài khoản test nào!</p>";
}

// 3. Kiểm tra lại sau khi xóa
echo "<h3>3. Kiểm tra lại sau khi xóa</h3>";

$sql = "SELECT COUNT(*) as count FROM nguoi_dung 
        WHERE ho_ten LIKE 'GV.%' 
           OR email LIKE '%example.com%' 
           OR ho_ten LIKE '%Test%'";

$result = $conn->query($sql);
$row = $result->fetch_assoc();
$remainingCount = $row['count'];

if ($remainingCount == 0) {
    echo "<p>✅ Không còn tài khoản test nào trong database</p>";
} else {
    echo "<p>⚠️ Vẫn còn $remainingCount tài khoản test</p>";
}

// 4. Hiển thị tất cả giảng viên còn lại
echo "<h3>4. Danh sách giảng viên còn lại</h3>";

$sql = "SELECT nd.ho_ten, nd.email FROM nguoi_dung nd 
        WHERE nd.vai_tro_id = 1 
        ORDER BY nd.ho_ten";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<p>📋 Còn lại " . $result->num_rows . " giảng viên:</p>";
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>{$row['ho_ten']} ({$row['email']})</li>";
    }
    echo "</ul>";
} else {
    echo "<p>⚠️ Không còn giảng viên nào trong hệ thống</p>";
    echo "<p>💡 Hãy chạy <code>php import_24_fixed.php</code> để thêm 24 giảng viên thật</p>";
}

$conn->close();

echo "<hr>";
echo "<h3>📋 Bước tiếp theo</h3>";
echo "<ol>";
echo "<li><strong>Refresh trang:</strong> Vào <code>danh_sach_de_tai_cong_khai.php</code> và refresh (Ctrl+F5)</li>";
echo "<li><strong>Kiểm tra dropdown:</strong> Mở dropdown giảng viên - không còn 'GV. Test'</li>";
echo "<li><strong>Xóa cache:</strong> Xóa cache trình duyệt nếu vẫn thấy</li>";
echo "</ol>";

echo "<div style='background: #e7f3ff; padding: 15px; border-left: 4px solid #2196F3; margin: 15px 0;'>";
echo "<h4>🔧 Nếu vẫn thấy 'GV. Test':</h4>";
echo "<p>Có thể do cache trình duyệt. Hãy:</p>";
echo "<ul>";
echo "<li>Nhấn <strong>Ctrl + Shift + R</strong> để hard refresh</li>";
echo "<li>Hoặc mở trang trong cửa sổ ẩn danh (Incognito)</li>";
echo "<li>Hoặc xóa cache trình duyệt hoàn toàn</li>";
echo "</ul>";
echo "</div>";
?>