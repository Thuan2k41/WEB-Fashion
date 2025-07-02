<?php
require_once '../handlers/dbcon.php';
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 10px;
        }
        .btn-dark {
            background-color:rgb(155, 162, 168);
            border: none;
        }
        .btn-dark:hover {
            background-color: #23272b;
        }
    </style>
</head>
<body>';
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['token'])) {
    $token = $_GET['token'];

    // Kiểm tra token hợp lệ
    $stmt = $con->prepare("SELECT id FROM users WHERE reset_token = ? AND reset_token_expiry > NOW()");
    if (!$stmt) {
        die("SQL Error: " . $con->error);
    }
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Hiển thị form nhập mật khẩu mới
        echo '<div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg">
                    <div class="card-header text-center bg-secondary text-white">
                        <h4>Đặt lại mật khẩu</h4>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <input type="hidden" name="token" value="' . htmlspecialchars($token) . '">
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới:</label>
                                <input type="password" id="password" name="password" class="form-control" placeholder="Nhập mật khẩu mới" required>
                            </div>
                            <button type="submit" class="btn btn-dark w-100">Đặt lại mật khẩu</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
      </div>';
    } else {
        echo "Invalid or expired token!";
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && isset($_POST['password'])) {
    $token = $_POST['token'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // Cập nhật mật khẩu
    $stmt = $con->prepare("UPDATE users SET password = ?, reset_token = NULL, reset_token_expiry = NULL WHERE reset_token = ?");
    if (!$stmt) {
        die("SQL Error: " . $con->error);
    }
    $stmt->bind_param("ss", $password, $token);
    if ($stmt->execute()) {
        echo "<script>
            alert('Mật khẩu đã được đặt lại!');
            window.location.href = '../template/log-in.html';
        </script>";
    } else {
        echo "Đặt lại mật khẩu thất bại!";
    }
}
?>