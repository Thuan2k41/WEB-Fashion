<?php
session_start();
require_once 'dbcon.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    if ($action === 'update') {
        // Xử lý sửa thông tin khách hàng
        $id = intval($_POST['id']);
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);

        // Cập nhật thông tin khách hàng
        $query = "UPDATE users SET username = ?, email = ?, phone = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param("sssi", $name, $email, $phone, $id);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Thông tin khách hàng đã được cập nhật.";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        }

        header("Location: ../template/admin_home.php");
        exit;

    } elseif ($action === 'delete') {
        // Xử lý xóa khách hàng
        $id = intval($_POST['id']);

        // Kiểm tra xem người dùng có tồn tại không
        $checkQuery = "SELECT id FROM users WHERE id = ?";
        $stmt = $con->prepare($checkQuery);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // Xóa người dùng
            $deleteQuery = "DELETE FROM users WHERE id = ?";
            $stmt = $con->prepare($deleteQuery);
            $stmt->bind_param("i", $id);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Người dùng đã được xóa.']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Không thể xóa người dùng.']);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Người dùng không tồn tại.']);
        }
    } 
    elseif ($action === 'add') {
        // Xử lý thêm khách hàng mới
        $name = trim($_POST['name']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);

        // Kiểm tra dữ liệu hợp lệ
        if (empty($name) || empty($email) || empty($phone) || empty($password)) {
            $_SESSION['error'] = "Vui lòng điền đầy đủ thông tin.";
            header("Location: ../template/admin_home.php");
            exit;
        }

        // Hash mật khẩu
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        // Thêm khách hàng vào cơ sở dữ liệu
        $query = "INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param("ssss", $name, $email, $phone, $hashedPassword);

        if ($stmt->execute()) {
            $_SESSION['success'] = "Khách hàng mới đã được thêm.";
        } else {
            $_SESSION['error'] = "Có lỗi xảy ra. Vui lòng thử lại.";
        }

        header("Location: ../template/admin_home.php");
        exit;
    }
    
}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['search'])) {
    $search = trim($_GET['search']);

    // Truy vấn danh sách khách hàng theo từ khóa tìm kiếm
    $query = "SELECT id, username, email, phone FROM users WHERE username LIKE ? OR email LIKE ? OR phone LIKE ?";
    $stmt = $con->prepare($query);
    $searchTerm = "%$search%";
    $stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
    $stmt->execute();
    $result = $stmt->get_result();
    $customers = $result->fetch_all(MYSQLI_ASSOC);
} else {
    // Nếu không có từ khóa tìm kiếm, lấy toàn bộ danh sách khách hàng
    $query = "SELECT id, username, email, phone FROM users";
    $result = $con->query($query);
    $customers = $result->fetch_all(MYSQLI_ASSOC);
}
?>