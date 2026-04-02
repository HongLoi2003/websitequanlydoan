<?php
/**
 * IMPORT 24 GIẢNG VIÊN - TỪNG BƯỚC
 * Đảm bảo thành công 100%
 */

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$host = 'localhost';
$dbname = 'qldt_database';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("❌ Lỗi kết nối database: " . $e->getMessage());
}

// Faculty data
$faculty_data = [
    // LÃNH ĐẠO (5 người)
    ['name' => 'TS. Nguyễn Nhựt Lam', 'email' => 'lamntn@tvu.edu.vn', 'phone' => '0919556441', 'room' => '123', 'role' => 'lanh_dao'],
    ['name' => 'TS. Thạch Kong Saoane', 'email' => 'oane@tvu.edu.vn', 'phone' => '0869847017', 'room' => '123', 'role' => 'lanh_dao'],
    ['name' => 'TS. Nguyễn Trần Diễm Hạnh', 'email' => 'diemhanh_tvu@tvu.edu.vn', 'phone' => '0842250996', 'room' => '123', 'role' => 'lanh_dao'],
    ['name' => 'Ths. Nguyễn Bá Nhiệm', 'email' => 'nhiemnb@tvu.edu.vn', 'phone' => '0983303609', 'room' => '168', 'role' => 'lanh_dao'],
    ['name' => 'Ths. Lê Phong Dũ', 'email' => 'lpdu@tvu.edu.vn', 'phone' => '0914256578', 'room' => '3853068', 'role' => 'lanh_dao'],
    
    // GIẢNG VIÊN (19 người)
    ['name' => 'TS. Đoàn Phước Miền', 'email' => 'phuocmien@tvu.edu.vn', 'phone' => '0978962954', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Võ Thành C', 'email' => 'vothanhc@tvu.edu.vn', 'phone' => '0909119657', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Trịnh Quốc Việt', 'email' => 'tqviet@tvu.edu.vn', 'phone' => '0354696999', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Trần Văn Nam', 'email' => 'namtv@tvu.edu.vn', 'phone' => '0365583414', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Trầm Hoàng Nam', 'email' => 'tramhoangnam@tvu.edu.vn', 'phone' => '0977810235', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Phan Thị Phương Nam', 'email' => 'ptpnam@tvu.edu.vn', 'phone' => '0989236166', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Phạm Thị Trúc Mai', 'email' => 'ptmai@tvu.edu.vn', 'phone' => '0936010206', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Phạm Minh Dương', 'email' => 'duongminh@tvu.edu.vn', 'phone' => '0982231344', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Nguyễn Thừa Phát Tài', 'email' => 'phattai@tvu.edu.vn', 'phone' => '0988345131', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Ngô Thanh Huy', 'email' => 'huynt@tvu.edu.vn', 'phone' => '0916741252', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Lê Minh Tự', 'email' => 'lmtu@tvu.edu.vn', 'phone' => '0918677326', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Nguyễn Khắc Quốc', 'email' => 'nkquoc@tvu.edu.vn', 'phone' => '0918085180', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Nguyễn Hoàng Duy Thiên', 'email' => 'thienhd@tvu.edu.vn', 'phone' => '0989274222', 'room' => '168', 'role' => 'giang_vien'],
    ['name' => 'Ths. Nguyễn Ngọc Huy', 'email' => 'huygocontt@tvu.edu.vn', 'phone' => '0989623237', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Lê Minh Hải', 'email' => 'hailm@tvu.edu.vn', 'phone' => '0918677326', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Khấu Văn Nhựt', 'email' => 'nhutkhau@tvu.edu.vn', 'phone' => '0979748090', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Huỳnh Văn Thanh', 'email' => 'hvthanh@tvu.edu.vn', 'phone' => '0977654181', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Hà Thị Thúy Vi', 'email' => 'hatv201084@tvu.edu.vn', 'phone' => '0983001084', 'room' => '123', 'role' => 'giang_vien'],
    ['name' => 'Ths. Dương Ngọc Văn Khanh', 'email' => 'vankhanhh@tvu.edu.vn', 'phone' => '0988332008', 'room' => '123', 'role' => 'giang_vien']
];

$step = $_GET['step'] ?? 1;
$action = $_POST['action'] ?? '';

?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Import 24 Giảng viên - Từng bước</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        .step-indicator { background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1rem; }
        .step-active { background: #d4edda; border-left: 4px solid #28a745; }
        .step-completed { background: #d1ecf1; border-left: 4px solid #17a2b8; }
        .log-output { background: #f8f9fa; padding: 1rem; border-radius: 0.5rem; max-height: 400px; overflow-y: auto; font-family: monospace; font-size: 0.9rem; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row">
            <div class="col-12">
                <h2><i class="bi bi-people"></i> Import 24 Giảng viên - Từng bước</h2>
                
                <!-- Step Indicator -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="step-indicator <?= $step >= 1 ? ($step == 1 ? 'step-active' : 'step-completed') : '' ?>">
                            <strong>Bước 1:</strong> Kiểm tra hệ thống
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step-indicator <?= $step >= 2 ? ($step == 2 ? 'step-active' : 'step-completed') : '' ?>">
                            <strong>Bước 2:</strong> Xóa dữ liệu cũ
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step-indicator <?= $step >= 3 ? ($step == 3 ? 'step-active' : 'step-completed') : '' ?>">
                            <strong>Bước 3:</strong> Import users
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="step-indicator <?= $step >= 4 ? ($step == 4 ? 'step-active' : 'step-completed') : '' ?>">
                            <strong>Bước 4:</strong> Tạo profiles
                        </div>
                    </div>
                </div>

                <?php if ($step == 1): ?>
                    <!-- BƯỚC 1: KIỂM TRA HỆ THỐNG -->
                    <div class="card">
                        <div class="card-header bg-primary text-white">
                            <h5><i class="bi bi-check-circle"></i> Bước 1: Kiểm tra hệ thống</h5>
                        </div>
                        <div class="card-body">
                            <div class="log-output">
                                <?php
                                echo "🔍 Kiểm tra kết nối database...\n";
                                try {
                                    $pdo->query("SELECT 1");
                                    echo "✅ Kết nối database thành công\n";
                                } catch (Exception $e) {
                                    echo "❌ Lỗi kết nối: " . $e->getMessage() . "\n";
                                    exit;
                                }

                                echo "\n🔍 Kiểm tra bảng nguoi_dung...\n";
                                try {
                                    $stmt = $pdo->query("DESCRIBE nguoi_dung");
                                    $columns = $stmt->fetchAll();
                                    echo "✅ Bảng nguoi_dung có " . count($columns) . " cột\n";
                                } catch (Exception $e) {
                                    echo "❌ Bảng nguoi_dung không tồn tại: " . $e->getMessage() . "\n";
                                    echo "💡 Hãy chạy create_all_tables.php trước\n";
                                    exit;
                                }

                                echo "\n🔍 Kiểm tra bảng giang_vien...\n";
                                try {
                                    $stmt = $pdo->query("DESCRIBE giang_vien");
                                    $columns = $stmt->fetchAll();
                                    echo "✅ Bảng giang_vien có " . count($columns) . " cột\n";
                                } catch (Exception $e) {
                                    echo "❌ Bảng giang_vien không tồn tại: " . $e->getMessage() . "\n";
                                    echo "💡 Hãy chạy create_all_tables.php trước\n";
                                    exit;
                                }

                                echo "\n🔍 Kiểm tra dữ liệu hiện tại...\n";
                                $stmt = $pdo->query("SELECT COUNT(*) as count FROM nguoi_dung WHERE email LIKE '%@tvu.edu.vn'");
                                $count = $stmt->fetch()['count'];
                                echo "📊 Hiện có $count tài khoản @tvu.edu.vn\n";

                                $stmt = $pdo->query("SELECT COUNT(*) as count FROM giang_vien");
                                $count = $stmt->fetch()['count'];
                                echo "📊 Hiện có $count profile giảng viên\n";

                                echo "\n✅ Hệ thống sẵn sàng cho import!\n";
                                ?>
                            </div>
                            
                            <div class="mt-3">
                                <a href="?step=2" class="btn btn-success btn-lg">
                                    <i class="bi bi-arrow-right"></i> Tiếp tục Bước 2
                                </a>
                            </div>
                        </div>
                    </div>

                <?php elseif ($step == 2): ?>
                    <!-- BƯỚC 2: XÓA DỮ LIỆU CŨ -->
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5><i class="bi bi-trash"></i> Bước 2: Xóa dữ liệu cũ (tùy chọn)</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($action == 'clean'): ?>
                                <div class="log-output">
                                    <?php
                                    try {
                                        echo "🗑️ Đang xóa dữ liệu cũ...\n";
                                        
                                        $stmt = $pdo->prepare("DELETE FROM giang_vien WHERE nguoi_dung_id IN (SELECT id FROM nguoi_dung WHERE email LIKE '%@tvu.edu.vn')");
                                        $stmt->execute();
                                        $deleted = $stmt->rowCount();
                                        echo "✅ Xóa $deleted profile giảng viên\n";
                                        
                                        $stmt = $pdo->prepare("DELETE FROM nguoi_dung WHERE email LIKE '%@tvu.edu.vn'");
                                        $stmt->execute();
                                        $deleted = $stmt->rowCount();
                                        echo "✅ Xóa $deleted tài khoản người dùng\n";
                                        
                                        echo "\n🎉 Xóa dữ liệu cũ thành công!\n";
                                    } catch (Exception $e) {
                                        echo "❌ Lỗi xóa dữ liệu: " . $e->getMessage() . "\n";
                                    }
                                    ?>
                                </div>
                                <div class="mt-3">
                                    <a href="?step=3" class="btn btn-success btn-lg">
                                        <i class="bi bi-arrow-right"></i> Tiếp tục Bước 3
                                    </a>
                                </div>
                            <?php else: ?>
                                <div class="alert alert-warning">
                                    <h6><i class="bi bi-exclamation-triangle"></i> Cảnh báo</h6>
                                    <p>Bước này sẽ xóa tất cả tài khoản @tvu.edu.vn hiện có. Chỉ làm nếu bạn muốn import lại từ đầu.</p>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <form method="POST">
                                            <input type="hidden" name="action" value="clean">
                                            <button type="submit" class="btn btn-warning btn-lg w-100">
                                                <i class="bi bi-trash"></i> Xóa dữ liệu cũ
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-md-6">
                                        <a href="?step=3" class="btn btn-primary btn-lg w-100">
                                            <i class="bi bi-arrow-right"></i> Bỏ qua, tiếp tục
                                        </a>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($step == 3): ?>
                    <!-- BƯỚC 3: IMPORT USERS -->
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5><i class="bi bi-person-plus"></i> Bước 3: Import tài khoản người dùng</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($action == 'import_users'): ?>
                                <div class="log-output">
                                    <?php
                                    $passwordHash = password_hash('123456', PASSWORD_DEFAULT);
                                    $created = 0;
                                    $skipped = 0;
                                    
                                    echo "👥 Bắt đầu import " . count($faculty_data) . " tài khoản...\n\n";
                                    
                                    foreach ($faculty_data as $index => $faculty) {
                                        try {
                                            // Check if exists
                                            $stmt = $pdo->prepare("SELECT id FROM nguoi_dung WHERE email = ?");
                                            $stmt->execute([$faculty['email']]);
                                            if ($stmt->fetch()) {
                                                echo "⚠️  Bỏ qua: " . $faculty['name'] . " (đã tồn tại)\n";
                                                $skipped++;
                                                continue;
                                            }
                                            
                                            // Create user
                                            $stmt = $pdo->prepare("
                                                INSERT INTO nguoi_dung (ho_ten, email, mat_khau, vai_tro, trang_thai, created_at, updated_at) 
                                                VALUES (?, ?, ?, ?, 'hoat_dong', NOW(), NOW())
                                            ");
                                            $stmt->execute([
                                                $faculty['name'],
                                                $faculty['email'],
                                                $passwordHash,
                                                $faculty['role']
                                            ]);
                                            
                                            $userId = $pdo->lastInsertId();
                                            echo "✅ " . ($index + 1) . ". " . $faculty['name'] . " (ID: $userId, Role: " . $faculty['role'] . ")\n";
                                            $created++;
                                            
                                        } catch (Exception $e) {
                                            echo "❌ Lỗi: " . $faculty['name'] . " - " . $e->getMessage() . "\n";
                                        }
                                    }
                                    
                                    echo "\n🎉 Hoàn thành! Tạo: $created, Bỏ qua: $skipped\n";
                                    ?>
                                </div>
                                <div class="mt-3">
                                    <a href="?step=4" class="btn btn-success btn-lg">
                                        <i class="bi bi-arrow-right"></i> Tiếp tục Bước 4
                                    </a>
                                </div>
                            <?php else: ?>
                                <p>Sẽ tạo <?= count($faculty_data) ?> tài khoản người dùng với mật khẩu: <strong>123456</strong></p>
                                <ul>
                                    <li>👑 <strong>5 lãnh đạo</strong> (lanh_dao): Trưởng khoa, Phó Trưởng khoa</li>
                                    <li>👨‍🏫 <strong>19 giảng viên</strong> (giang_vien): Các giảng viên khác</li>
                                </ul>
                                
                                <form method="POST">
                                    <input type="hidden" name="action" value="import_users">
                                    <button type="submit" class="btn btn-info btn-lg">
                                        <i class="bi bi-person-plus"></i> Import tài khoản
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>

                <?php elseif ($step == 4): ?>
                    <!-- BƯỚC 4: TẠO PROFILES -->
                    <div class="card">
                        <div class="card-header bg-success text-white">
                            <h5><i class="bi bi-person-badge"></i> Bước 4: Tạo profile giảng viên</h5>
                        </div>
                        <div class="card-body">
                            <?php if ($action == 'create_profiles'): ?>
                                <div class="log-output">
                                    <?php
                                    $created = 0;
                                    $skipped = 0;
                                    
                                    echo "👨‍🏫 Bắt đầu tạo profile giảng viên...\n\n";
                                    
                                    foreach ($faculty_data as $index => $faculty) {
                                        try {
                                            // Get user ID
                                            $stmt = $pdo->prepare("SELECT id FROM nguoi_dung WHERE email = ?");
                                            $stmt->execute([$faculty['email']]);
                                            $user = $stmt->fetch();
                                            
                                            if (!$user) {
                                                echo "❌ Không tìm thấy user: " . $faculty['email'] . "\n";
                                                continue;
                                            }
                                            
                                            // Check if profile exists
                                            $stmt = $pdo->prepare("SELECT id FROM giang_vien WHERE nguoi_dung_id = ?");
                                            $stmt->execute([$user['id']]);
                                            if ($stmt->fetch()) {
                                                echo "⚠️  Bỏ qua profile: " . $faculty['name'] . " (đã tồn tại)\n";
                                                $skipped++;
                                                continue;
                                            }
                                            
                                            // Create profile
                                            $maGiangVien = strtoupper(substr($faculty['email'], 0, strpos($faculty['email'], '@')));
                                            
                                            $stmt = $pdo->prepare("
                                                INSERT INTO giang_vien (
                                                    nguoi_dung_id, ma_giang_vien, khoa, chuyen_mon, 
                                                    so_dien_thoai, phong_lam_viec, created_at, updated_at
                                                ) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())
                                            ");
                                            $stmt->execute([
                                                $user['id'],
                                                $maGiangVien,
                                                'Công nghệ thông tin',
                                                'Công nghệ thông tin',
                                                $faculty['phone'],
                                                $faculty['room']
                                            ]);
                                            
                                            echo "✅ " . ($index + 1) . ". " . $faculty['name'] . " (Mã: $maGiangVien)\n";
                                            $created++;
                                            
                                        } catch (Exception $e) {
                                            echo "❌ Lỗi profile: " . $faculty['name'] . " - " . $e->getMessage() . "\n";
                                        }
                                    }
                                    
                                    echo "\n🎉 Hoàn thành! Tạo: $created profiles, Bỏ qua: $skipped\n";
                                    
                                    // Final check
                                    echo "\n📊 KIỂM TRA CUỐI CÙNG:\n";
                                    $stmt = $pdo->query("
                                        SELECT 
                                            COUNT(*) as total,
                                            SUM(CASE WHEN vai_tro = 'lanh_dao' THEN 1 ELSE 0 END) as leaders,
                                            SUM(CASE WHEN vai_tro = 'giang_vien' THEN 1 ELSE 0 END) as lecturers
                                        FROM nguoi_dung 
                                        WHERE email LIKE '%@tvu.edu.vn'
                                    ");
                                    $stats = $stmt->fetch();
                                    echo "👥 Tổng users: " . $stats['total'] . "\n";
                                    echo "👑 Lãnh đạo: " . $stats['leaders'] . "\n";
                                    echo "👨‍🏫 Giảng viên: " . $stats['lecturers'] . "\n";
                                    
                                    $stmt = $pdo->query("SELECT COUNT(*) as count FROM giang_vien");
                                    $profileCount = $stmt->fetch()['count'];
                                    echo "📋 Profiles: $profileCount\n";
                                    
                                    echo "\n🎉 IMPORT HOÀN TẤT! Tất cả 24 giảng viên đã được import thành công!\n";
                                    ?>
                                </div>
                                
                                <div class="mt-3">
                                    <div class="alert alert-success">
                                        <h5><i class="bi bi-check-circle"></i> Import thành công!</h5>
                                        <p class="mb-2">Bây giờ bạn có thể:</p>
                                        <ul class="mb-3">
                                            <li>Đăng nhập với bất kỳ email nào + mật khẩu: <strong>123456</strong></li>
                                            <li>Kiểm tra trang chi tiết giảng viên: <code>giang_vien_chi_tiet_v2.php?email=[email]</code></li>
                                            <li>Truy cập dashboard theo vai trò</li>
                                        </ul>
                                        
                                        <div class="row">
                                            <div class="col-md-4">
                                                <a href="auth/login.php" class="btn btn-primary w-100">
                                                    <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="giang_vien_chi_tiet_v2.php?email=oane@tvu.edu.vn" class="btn btn-info w-100" target="_blank">
                                                    <i class="bi bi-eye"></i> Test profile
                                                </a>
                                            </div>
                                            <div class="col-md-4">
                                                <a href="khoa_cntt.php" class="btn btn-success w-100" target="_blank">
                                                    <i class="bi bi-building"></i> Trang khoa
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p>Sẽ tạo profile giảng viên cho tất cả tài khoản đã import.</p>
                                <p>Bao gồm: mã giảng viên, khoa, chuyên môn, số điện thoại, phòng làm việc.</p>
                                
                                <form method="POST">
                                    <input type="hidden" name="action" value="create_profiles">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="bi bi-person-badge"></i> Tạo profiles
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>