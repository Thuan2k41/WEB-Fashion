<?php
session_start();
require_once('dbcon.php');

if (!isset($_SESSION['email'])) {
    header("Location: ../template/log-in.html");
    exit;
}

// Lấy user_id từ username
$email = $_SESSION['email'];
$userQuery = "SELECT id FROM users WHERE email = '$email'";
$userResult = mysqli_query($con, $userQuery);
$user = mysqli_fetch_assoc($userResult);
$user_id = $user['id'];

// Xử lý POST thêm vào giỏ hàng
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $quantity = intval($_POST['quantity']);
    $size_id = intval($_POST['size_id']);

    // Kiểm tra nếu sản phẩm đã có trong giỏ (cùng size)
    $checkQuery = "SELECT * FROM cart WHERE user_id = $user_id AND product_id = $product_id AND size_id = $size_id";
    $checkResult = mysqli_query($con, $checkQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        // Cập nhật số lượng
        $updateQuery = "UPDATE cart SET quantity = quantity + $quantity 
                        WHERE user_id = $user_id AND product_id = $product_id AND size_id = $size_id";
        mysqli_query($con, $updateQuery);
    } else {
        // Thêm mới
        $insertQuery = "INSERT INTO cart (user_id, product_id, size_id, quantity)
                        VALUES ($user_id, $product_id, $size_id, $quantity)";
        mysqli_query($con, $insertQuery);
    }

    // Trả về JSON nếu là AJAX
    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Đã thêm vào giỏ hàng'
        ]);
        exit;
    }

    // Không phải AJAX → chuyển về cart
    header("Location: ../template/cart.php");
    exit;
}

// Nếu không phải POST → redirect về trang sản phẩm
header("Location: ../template/products.php");
exit;